<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-10-14 10:31:38
 */

namespace Yeskn\MainBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Yeskn\MainBundle\Services\SocketPushService;
use Yeskn\Support\Command\AbstractCommand;

class PushMessageCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('push-message');
        $this->addOption('message', null, InputOption::VALUE_REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var SocketPushService $socket */
        $socket = $this->get('socket.push');

        $socket->pushAll('broadcast', [
            'message' => $input->getOption('message')
        ]);
    }
}
