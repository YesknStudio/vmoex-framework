<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2019-03-27 23:00:29
 */

namespace Yeskn\Support\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Yeskn\MainBundle\Form\Logic\OptionsLogic;

class UpCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('up');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var OptionsLogic $optionsLogic */
        $optionsLogic = $this->get(OptionsLogic::class);

        $optionsLogic->removeOptions('maintain');

        $fs = new Filesystem();

        $fs->remove($this->parameter('kernel.project_dir') . '/var/maintain');

        $output->writeln('<info>the application is up now.</info>');
    }
}
