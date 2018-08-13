<?php

/*
 * This file is part of project yeskn/vmoex.
 *
 * (c) Jaggle <jaggle@yeskn.com>
 *
 * created at 2018-05-27 02:04:51
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yeskn\WebBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Yeskn\WebBundle\Entity\User;

class ResetPasswordCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('reset-password');
        $this->addArgument('username', InputArgument::REQUIRED, '帐号');
        $this->addArgument('password', InputArgument::OPTIONAL, '新密码', '123456');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $userRepository = $container->get('doctrine')->getRepository('YesknWebBundle:User');
        /**
         * @var User $user
         */
        $user = $userRepository->findOneBy(['username' => $input->getArgument('username')]);

        $password = $container->get('security.password_encoder')
            ->encodePassword($user, $user->getPassword());
        $user->setPassword($password);

        $container->get('doctrine')->getManager()->flush();

        $ss = new SymfonyStyle($input, $output);

        $ss->success('密码已重置');
    }
}