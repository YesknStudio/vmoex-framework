<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-16 13:36:05
 */

namespace Yeskn\AdminBundle\CrudEvent;

use Yeskn\AdminBundle\Services\LoadTranslationService;
use Yeskn\MainBundle\Entity\Translation;

class FinishEditTranslationEvent implements CrudEventInterface
{
    private $entity;

    private $loadService;

    public function __construct(Translation $entity, LoadTranslationService $loadService)
    {
        $this->entity = $entity;
        $this->loadService = $loadService;

    }

    public function execute()
    {
        $this->loadService->execute();
    }
}