<?php
/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jaggle
 * Create: 2018-07-19 18:04:09
 */

namespace Yeskn\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Yeskn\MainBundle\Entity\Message;

class BlogCreateWorkerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('blog:create-worker');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        while (true) {
            $do = $this->getContainer()->get('doctrine');
            $push = $this->getContainer()->get('socket.push');

            $user = $do->getRepository('YesknMainBundle:User')->findOneBy([
                'status' => 1,
                'type' => 'user'
            ]);

            $command = $this->getApplication()->find('blog:create');

            $arguments = [
                '--username' => $user->getUsername(),
                '--password' => $user->getPassword()
            ];

            $commandInput = new ArrayInput($arguments);

            $command->run($commandInput, $output);

            $push->pushNewMessage(new Message());

            sleep(3);
        }
    }
}