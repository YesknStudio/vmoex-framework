<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-14 23:46:25
 */

namespace Yeskn\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Yeskn\AdminBundle\Services\LoadTranslationService;

class LoadTranslationCommand extends ContainerAwareCommand
{
    /**
     * @var LoadTranslationService
     */
    private $loadTranslationService;

    public function __construct(LoadTranslationService $loadTranslationService )
    {
        $this->loadTranslationService = $loadTranslationService;
        $this->configure();

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('load-translation');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->loadTranslationService->execute();
        $output->writeln('finished!');
    }
}