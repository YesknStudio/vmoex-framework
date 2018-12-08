<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-16 17:10:07
 */

namespace Yeskn\AdminBundle\CrudEvent;

use Yeskn\MainBundle\Entity\Translation;

class ProcessDeleteTranslationEvent extends AbstractCrudEntityEvent
{
    /**
     * @var Translation
     */
    protected $entity;

    public function execute()
    {
        if (!$this->entity->isCanDelete()) {
            throw new \Exception('该词条为系统设置，无法删除');
        }
    }
}
