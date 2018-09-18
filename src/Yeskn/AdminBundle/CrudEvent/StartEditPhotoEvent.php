<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-18 22:02:17
 */

namespace Yeskn\AdminBundle\CrudEvent;

use Symfony\Component\HttpFoundation\File\File;
use Yeskn\MainBundle\Entity\Photo;

class StartEditPhotoEvent extends AbstractCrudEntityEvent
{
    /**
     * @var Photo
     */
    protected $entity;

    public static $oldProperty;

    public function execute()
    {
        $entityObj = $this->entity;

        if ($oldCover = $entityObj->getFile()) {
            $entityObj->setFile(new File($oldCover, false));
        } else {
            $oldCover = null;
        }

        return self::$oldProperty = [
            'file' => $oldCover
        ];
    }
}