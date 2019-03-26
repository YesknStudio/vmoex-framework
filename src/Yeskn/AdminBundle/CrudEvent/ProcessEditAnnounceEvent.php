<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-16 12:39:38
 */

namespace Yeskn\AdminBundle\CrudEvent;

use Yeskn\MainBundle\Entity\Announce;

class ProcessEditAnnounceEvent extends AbstractCrudEntityEvent
{
    /**
     * @var Announce
     */
    protected $entity;

    public function execute()
    {
        $entityObj = $this->entity;

        $content = strip_tags($entityObj->getContent(),
            'b, strong,i,em,font,small,bold,span,p'
        );

        $entityObj->setContent($content);
    }
}
