<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-16 14:24:37
 */

namespace Yeskn\AdminBundle\CrudEvent;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Yeskn\MainBundle\Entity\User;
use Intervention\Image\ImageManagerStatic as Image;

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

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, $projectDir)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->oldValue = StartEditUserEvent::$odlProperty;
        $this->webRoot = $projectDir . '/web/';
    }

    public function execute()
    {
        $user = $this->entity;

        if (empty($user->getId())) {
            if (empty($user->getPassword())) {
                throw new \Exception('新增用户时，密码不能为空');
            }

            if (empty($user->getAvatar())) {
                throw new \Exception('新增用户时，头像不能为空');
            }

            $user->setRegisterAt(new \DateTime());
            $user->setLoginAt(new \DateTime());
        }

        if (!empty($user->getPassword())) {
            $user->setSalt(md5(uniqid()));
            $password = $this->passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
        } else {
            $user->setPassword($this->oldValue['password']);
        }

        /** @var File $file */
        if ($file = $user->getAvatar()) {
            $extension = $file->guessExtension();
            $fileName = 'avatar/' . time() . mt_rand(1000, 9999) . '.' . $extension;

            $targetPath = $this->webRoot .  '/' . $fileName;

            $fs = new Filesystem();
            $fs->copy($file->getRealPath(), $targetPath);

            Image::configure(array('driver' => 'gd'));

            $image = Image::make($targetPath);
            $image->resize(100, 100)->save();

            $user->setAvatar($fileName);
        } else {
            $user->setAvatar($this->oldValue['avatar']);
        }
    }
}