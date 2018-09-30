<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-30 13:02:47
 */

namespace Yeskn\Support\EventListener;

use Symfony\Component\Console\Event\ConsoleCommandEvent;

class ConsoleCommandListener
{
    private $varDir;

    public function __construct($projectDir)
    {
        $this->varDir = rtrim($projectDir, '/') . '/var';
    }

    public function onConsoleCommand(ConsoleCommandEvent $event)
    {
        switch ($event->getCommand()->getName()) {
            case 'assetic:dump':
                $this->onAsseticDump();
                break;
            default:
                return ;
        }
    }

    protected function onAsseticDump()
    {
        $hash = substr(uniqid(), 0, 8);
        file_put_contents($this->varDir . '/assets_version', $hash);
    }
}