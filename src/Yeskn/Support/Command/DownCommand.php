<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2019-03-27 23:00:39
 */

namespace Yeskn\Support\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Yeskn\MainBundle\Form\Logic\OptionsLogic;

class DownCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('down');
        $this->addOption('day', null, InputOption::VALUE_OPTIONAL
            , '维护时长，单位：天', 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fs = new Filesystem();
        /** @var OptionsLogic $logic */
        $logic = $this->get(OptionsLogic::class);

        $day = $input->getOption('day');

        $maintain = [
            'maintain_start' => date('Y-m-d H:i:s'),
            'maintain_stop' => date('Y-m-d H:i:s', time() + $day*24*60*60)
        ];

        $logic->setOptions($maintain + ['maintain_enable' => true], 'maintain');

        $output->writeln('<comment>the application is down for ' .$day.' day now!</comment>');
    }
}
