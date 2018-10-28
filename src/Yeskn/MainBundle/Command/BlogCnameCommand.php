<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-10-28 15:11:32
 */

namespace Yeskn\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Yeskn\Support\Command\AbstractCommand;

class BlogCnameCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('blog:cname');

        $this->addOption('subdomain', null, InputOption::VALUE_REQUIRED );
        $this->addOption('cname', null, InputOption::VALUE_REQUIRED );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $subdomain = $input->getOption('subdomain');
        $cname = $input->getOption('cname');

        $twig = $this->getContainer()->get(EngineInterface::class);

        $stub301 = $twig->render('stub/nginx-301.html.twig', [
            'cname' => $cname,
            'subdomain' => $subdomain
        ]);

        $stubCname = $twig->render('stub/nginx-cname.html.twig', [
            'cname' =>  $cname,
            'subdomain' => $subdomain
        ]);

        $fileName301 = $this->parameter('nginx.cname_path') . '/301-' . $subdomain . '.conf';
        $fileNameCname = $this->parameter('nginx.cname_path') . '/cname-' . $subdomain . '.conf';

        file_put_contents($fileName301, $stub301);
        file_put_contents($fileNameCname, $stubCname);

        $process = new Process('nginx -s reload');
        $process->run();

        return true;
    }
}
