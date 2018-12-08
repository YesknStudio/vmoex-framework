<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-10-31 00:08:21
 */

namespace Yeskn\MainBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Yeskn\MainBundle\Entity\User;
use Yeskn\MainBundle\Repository\UserRepository;
use Yeskn\Support\Command\AbstractCommand;

class DownloadRemoteAvatarCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('download:remote-avatar');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        /** @var UserRepository $userRepo */
        $userRepo = $this->doctrine()->getRepository('YesknMainBundle:User');

        /** @var User[] $users */
        $users = $userRepo->createQueryBuilder('p')
            ->where('p.avatar LIKE :avatar')
            ->setParameter('avatar', 'http%')
            ->getQuery()
            ->getResult();

        foreach ($users as $user) {
            $fileName = md5($user->getUsername() . time()) . '.png';
            $file = $this->parameter('kernel.project_dir') . '/web/avatar/' . $fileName;

            $img = file_get_contents($user->getAvatar());
            file_put_contents($file, $img);

            $user->setAvatar('avatar/' . $fileName);

            $this->em()->flush();
        }
    }
}
