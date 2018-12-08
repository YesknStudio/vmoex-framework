<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-17 20:20:20
 */

namespace Yeskn\AdminBundle\CrudEvent;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Yeskn\MainBundle\Entity\Photo;
use Intervention\Image\ImageManagerStatic as Image;

class ProcessEditPhotoEvent extends AbstractCrudEntityEvent
{
    /**
     * @var Photo
     */
    protected $entity;

    private $webPath;

    private $oldFile;

    public function __construct($projectDir)
    {
        $this->webPath = $projectDir . '/web';
        $this->oldFile = StartEditPhotoEvent::$oldProperty['file'];
    }

    public function execute()
    {
        $entityObj = $this->entity;

        if (empty($entityObj->getId())) {
            $entityObj->setCreatedAt(new \DateTime());
        }

        /** @var UploadedFile $file */
        if ($file = $entityObj->getFile()) {
            $extension = $file->guessExtension();
            $fileName = 'upload/photo/' . time() . mt_rand(1000, 9999) . '.' . $extension;

            $targetPath = $this->webPath .  '/' . $fileName;

            $fs = new Filesystem();
            $fs->copy($file->getRealPath(), $targetPath);

            Image::configure(array('driver' => 'gd'));

            $image = Image::make($targetPath);
            $image->resize(200, 100)->save();

            $entityObj->setFile($fileName);
        } else if (!empty($this->oldFile)) {
            $entityObj->setFile($this->oldFile);
        } else {
            throw new \Exception('图片不能为空');
        }
    }
}
