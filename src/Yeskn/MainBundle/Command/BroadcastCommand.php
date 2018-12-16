<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jaggle
 * Create: 2018-10-14 10:31:38
 */

namespace Yeskn\MainBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Yeskn\MainBundle\Services\SocketPushService;
use Yeskn\Support\Command\AbstractCommand;

class BroadcastCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('broadcast');
        $this->setDescription('broadcast message to all online users');
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
