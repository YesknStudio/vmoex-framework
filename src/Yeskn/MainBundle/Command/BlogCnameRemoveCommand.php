<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-10-28 15:11:32
 */

namespace Yeskn\MainBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Yeskn\Support\Command\AbstractCommand;

class BlogCnameRemoveCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('blog:cname:remove');

        $this->addOption('subdomain', null, InputOption::VALUE_REQUIRED );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $subdomain = $input->getOption('subdomain');

        $fileName301 = $this->parameter('nginx.cname_path') . '/301-' . $subdomain . '.conf';
        $fileNameCname = $this->parameter('nginx.cname_path') . '/cname-' . $subdomain . '.conf';

        unlink($fileName301);
        unlink($fileNameCname);

        $process = new Process('nginx -s reload');
        $process->run();

        return true;
    }
}
