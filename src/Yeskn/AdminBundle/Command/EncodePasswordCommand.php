<?php

/**
 * This file is part of project project yeskn-studio/vmoex-framework.
 *
 * Author: Jaggle
 * Create: 2018-09-12 15:31:56
 */

namespace Yeskn\AdminBundle\Command;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Yeskn\MainBundle\Repository\UserRepository;

class EncodePasswordCommand extends ContainerAwareCommand
{
    /** @var UserRepository */
    private $userRepository;

    /** @var Registry */
    private $doctrine;

    protected function init()
    {
        $this->doctrine = $this->getContainer()->get('doctrine');

        $this->userRepository = $this->doctrine->getRepository('YesknMainBundle:User');
    }

    protected function configure()
    {
        $this->setName('change-password');

        $this->addOption('username', 'u', InputOption::VALUE_REQUIRED);
        $this->addOption('password', 'p', InputOption::VALUE_REQUIRED);

        $this->setDescription('change someone\'s password');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->init();

        $username = $input->getOption('username');
        $password = $input->getOption('password');

        $user = $this->userRepository->loadUserByUsernameOrEmail($username);

        $style = new SymfonyStyle($input, $output);

        if (empty($user)) {
            $style->error(sprintf('user with username %s does not exist!', $username));
            return ;
        }

        $encode = $this->getContainer()->get('security.password_encoder')
            ->encodePassword($user, $password);

        $user->setPassword($encode);

        $this->doctrine->getManager()->flush();

        $style->success(sprintf("the password for %s changed to : \n %s",
            $username,
            $password
        ));
    }
}
