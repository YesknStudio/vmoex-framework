<?php

/**
 * This file is part of project project yeskn-studio/vmoex-framework.
 *
 * Author: Jaggle
 * Create: 2018-09-14 23:46:25
 */

namespace Yeskn\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Yeskn\AdminBundle\Services\LoadTranslationService;

class TranslationLoadCommand extends ContainerAwareCommand
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
        $this->setName('translation:load');
        $this->setDescription('load translation from db to file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->loadTranslationService->execute();
        $output->writeln('finished!');
    }
}
