<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jaggle
 * Create: 2018-12-08 20:27:07
 */

namespace Yeskn\MainBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Yeskn\Support\Command\AbstractCommand;

class DoctrineDatabaseInitCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('doctrine:database:init');
        $this->setDescription('caution: only should be executed after installation!');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $conn = $this->em()->getConnection();
        $sql = file_get_contents($this->parameter('kernel.project_dir') . '/var/data/vmoex.sql');

        $conn->executeQuery($sql);


        $output->writeln('finished!');
    }
}
