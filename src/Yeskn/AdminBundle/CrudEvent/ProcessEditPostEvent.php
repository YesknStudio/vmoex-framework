<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-17 20:20:20
 */

namespace Yeskn\AdminBundle\CrudEvent;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Yeskn\MainBundle\Entity\Post;
use Intervention\Image\ImageManagerStatic as Image;

class ProcessEditPostEvent implements CrudEventInterface
{
    private $entity;

    private $webPath;

    private $oldCover;

    public function __construct(Post $entity, $projectDir, $oldProperty)
    {
        $this->entity = $entity;
        $this->webPath = $projectDir . '/web';
        $this->oldCover = $oldProperty['oldCover'];
    }

    public function execute()
    {
        $entityObj = $this->entity;

        /** @var UploadedFile $file */
        if ($file = $entityObj->getCover()) {
            $extension = $file->guessExtension();
            $fileName = 'upload/cover/' . time() . mt_rand(1000, 9999) . '.' . $extension;

            $targetPath = $this->webPath .  '/' . $fileName;

            $fs = new Filesystem();
            $fs->copy($file->getRealPath(), $targetPath);

            Image::configure(array('driver' => 'gd'));

            $image = Image::make($targetPath);
            $image->resize(200, 100)->save();

            $entityObj->setCover($fileName);
        } else if (!empty($this->oldCover)) {
            $entityObj->setCover($this->oldCover);
        } else {
            throw new \Exception('封面图不能为空');
        }
    }
}