<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-16 12:39:38
 */

namespace Yeskn\AdminBundle\CrudEvent;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Yeskn\MainBundle\Entity\Tab;
use Intervention\Image\ImageManagerStatic as Image;

class ProcessEditTabEvent extends AbstractCrudEntityEvent
{
    /**
     * @var Tab
     */
    protected $entity;

    private $webRoot;

    private $oldAvatar;

    public function __construct($projectDir)
    {
        $this->webRoot = $projectDir . '/web';
        $this->oldAvatar = StartEditTabEvent::$oldProperty;
    }

    public function execute()
    {
        $entityObj = $this->entity;

        if ($entityObj->getLevel() == 1) {
            $entityObj->setParent(null);
        }

        /** @var UploadedFile $file */
        if ($file = $entityObj->getAvatar()) {
            $extension = $file->guessExtension();
            $fileName = 'upload/' . time() . mt_rand(1000, 9999) . '.' . $extension;

            $targetPath = $this->webRoot .  '/' . $fileName;

            $fs = new Filesystem();
            $fs->copy($file->getRealPath(), $targetPath);

            Image::configure(array('driver' => 'gd'));

            $image = Image::make($targetPath);
            $image->resize(100, 100)->save();

            $entityObj->setAvatar($fileName);
        } else if (!empty($this->oldAvatar)) {
            $entityObj->setAvatar($this->oldAvatar);
        } else {
            throw new \Exception('板块标志不能为空');
        }
    }
}