<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-16 13:36:05
 */

namespace Yeskn\AdminBundle\CrudEvent;

use Yeskn\AdminBundle\Services\LoadTranslationService;

class FinishEditTranslationEvent extends AbstractCrudEntityEvent
{
    private $loadService;

    public function __construct(LoadTranslationService $loadService)
    {
        $this->loadService = $loadService;
    }

    public function execute()
    {
        $this->loadService->execute();
    }
}
