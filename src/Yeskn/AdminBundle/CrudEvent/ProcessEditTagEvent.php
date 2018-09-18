<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-16 12:37:11
 */

namespace Yeskn\AdminBundle\CrudEvent;

use Yeskn\MainBundle\Entity\Tag;

class ProcessEditTagEvent extends AbstractCrudEntityEvent
{
    /**
     * @var Tag
     */
    protected $entity;

    public function execute()
    {
        if (empty($this->entity->getId())) {
            $this->entity->setCreatedAt(new \DateTime());
        }
    }
}