<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-16 12:39:38
 */

namespace Yeskn\AdminBundle\CrudEvent;

use Symfony\Component\HttpFoundation\File\File;
use Yeskn\MainBundle\Entity\Tab;
use Yeskn\Support\File\ImageHandler;

class ProcessEditTabEvent extends AbstractCrudEntityEvent
{
    /**
     * @var Tab
     */
    protected $entity;

    private $webRoot;

    private $oldAvatar;

    private $imageHandler;

    public function __construct($projectDir, ImageHandler $imageHandler)
    {
        $this->webRoot = $projectDir . '/web';
        $this->oldAvatar = StartEditTabEvent::$oldProperty['avatar'];

        $imageHandler->setHeight(200);
        $imageHandler->setWidth(200);
        $this->imageHandler = $imageHandler;
    }

    public function execute()
    {
        $entityObj = $this->entity;

        if ($entityObj->getLevel() == 1) {
            $entityObj->setParent(null);
        }

        if ($entityObj->getAvatar() instanceof File) {
            $this->imageHandler->handle($entityObj, 'avatar');
        } else if (!empty($this->oldAvatar)) {
            $entityObj->setAvatar($this->oldAvatar);
        } else {
            throw new \Exception('板块标志不能为空');
        }
    }
}