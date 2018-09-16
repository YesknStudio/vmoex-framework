<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-16 12:37:11
 */

namespace Yeskn\AdminBundle\CrudEvent;

use Yeskn\MainBundle\Entity\Comment;

class ProcessEditCommentEvent implements CrudEventInterface
{
    private $entity;

    public function __construct(Comment $entity)
    {
        $this->entity = $entity;
    }

    public function execute()
    {
        if (empty($this->entity->getId())) {
            $this->entity->setCreatedAt(new \DateTime());
        }
    }
}