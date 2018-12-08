<?php

/**
 * This file is part of project vmoex-ex.
 *
 * Author: Jake
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
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $conn = $this->em()->getConnection();

        try {
            $sql = file_get_contents($this->parameter('kernel.project_dir') . '/var/data/vmoex.sql');

            $conn->executeQuery($sql);
        }catch (\Exception $exception) {
            throw $exception;
        }


        $output->writeln('finished!');
    }
}
