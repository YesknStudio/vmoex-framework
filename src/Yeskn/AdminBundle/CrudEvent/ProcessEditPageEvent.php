<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-16 12:37:11
 */

namespace Yeskn\AdminBundle\CrudEvent;

use Yeskn\MainBundle\Entity\Page;

class ProcessEditPageEvent extends AbstractCrudEntityEvent
{
    /**
     * @var Page
     */
    protected $entity;

    public function execute()
    {
        if (empty($this->entity->getId())) {
            $this->entity->setCreatedAt(new \DateTime());
        }

        $this->entity->setUpdatedAt(new \DateTime());
    }
}
