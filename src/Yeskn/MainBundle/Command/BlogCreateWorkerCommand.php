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
use Yeskn\MainBundle\Entity\Blog;

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

            $blog = $do->getRepository('YesknMainBundle:Blog')
                ->findOneBy(['status' => Blog::STATUS_QUEUEING], ['id' => 'ASC']);

            if (empty($blog)) {
                sleep(3);
                continue;
            }

            $command = $this->getApplication()->find('blog:create');

            $arguments = [
                '--username' => $blog->getUser()->getUsername(),
                '--password' => $blog->getPassword(),
                '--email' => $blog->getUser()->getEmail(),
                '--blogName' => $blog->getTitle(),
            ];

            $commandInput = new ArrayInput($arguments);

            $command->run($commandInput, $output);

            $blog->setStatus(Blog::STATUS_CREATED);
            $this->getContainer()->get('doctrine.orm.entity_manager')->flush();

            sleep(3);
        }
    }
}