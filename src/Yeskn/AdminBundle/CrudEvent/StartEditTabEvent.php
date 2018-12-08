<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-16 12:11:49
 */

namespace Yeskn\AdminBundle\CrudEvent;

use Symfony\Component\HttpFoundation\File\File;
use Yeskn\MainBundle\Entity\Tab;

class StartEditTabEvent extends AbstractCrudEntityEvent
{
    /**
     * @var Tab
     */
    protected $entity;

    public static $oldProperty;

    public function execute()
    {
        $entityObj = $this->entity;

        $oldAvatar = $entityObj->getAvatar();
        $entityObj->setAvatar(new File($oldAvatar, false));

        return self::$oldProperty = [
            'avatar' => $oldAvatar
        ];
    }

}
