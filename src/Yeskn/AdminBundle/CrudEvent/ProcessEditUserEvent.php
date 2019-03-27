<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-16 14:24:37
 */

namespace Yeskn\AdminBundle\CrudEvent;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Yeskn\MainBundle\Entity\User;
use Intervention\Image\ImageManagerStatic as Image;
use Yeskn\MainBundle\Services\RandomAvatarService;

class ProcessEditUserEvent extends AbstractCrudEntityEvent
{
    /**
     * @var User
     */
    protected $entity;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    private $oldValue;

    private $webRoot;

    private $avatarService;

    private $em;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, $projectDir
        , RandomAvatarService $avatarService
        , EntityManagerInterface $em
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->oldValue = StartEditUserEvent::$odlProperty;
        $this->webRoot = $projectDir . '/web';
        $this->avatarService = $avatarService;
        $this->em = $em;
    }

    public function execute()
    {
        $user = $this->entity;

        if (empty($user->getId())) {
            $user->setRegisterAt(new \DateTime());
            $user->setLoginAt(new \DateTime());
        }

        if (empty($user->getAvatar()) && empty($this->oldValue['avatar'])) {
            $this->avatarService->handle($user);
        }

        if (!empty($user->getPassword())) {
            $user->setSalt(md5(uniqid()));
            $password = $this->passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
        } else {
            $user->setPassword($this->oldValue['password']);
        }

        $check = $this->em->getRepository('YesknMainBundle:User')
            ->checkEmailAndUsername($user->getEmail(), $user->getUsername(), $user->getId());

        if ($check) {
            throw new BadRequestHttpException('用户名或者邮箱已经注册');
        }

        $user->setRemark($user->getRemark() ?: '');

        if ($file = $user->getAvatar()) {
            if ($file instanceof UploadedFile) {
                $extension = $file->guessExtension();
                $fileName = 'upload/avatar/' . time() . mt_rand(1000, 9999) . '.' . $extension;

                $targetPath = $this->webRoot .  '/' . $fileName;

                $fs = new Filesystem();
                $fs->copy($file->getRealPath(), $targetPath);

                Image::configure(array('driver' => 'gd'));

                $image = Image::make($targetPath);
                $image->resize(100, 100)->save();

                $user->setAvatar($fileName);
            }
        } else {
            $user->setAvatar($this->oldValue['avatar']);
        }
    }
}
