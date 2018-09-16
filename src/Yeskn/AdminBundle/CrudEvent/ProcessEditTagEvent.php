<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-16 12:37:11
 */

namespace Yeskn\AdminBundle\CrudEvent;

use Yeskn\MainBundle\Entity\Tag;

class ProcessEditTagEvent implements CrudEventInterface
{
    private $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    public function execute()
    {
        if (empty($this->tag->getId())) {
            $this->tag->setCreatedAt(new \DateTime());
        }
    }
}