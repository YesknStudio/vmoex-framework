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
use Yeskn\MainBundle\Entity\Post;
use Intervention\Image\ImageManagerStatic as Image;

class ProcessEditPostEvent extends AbstractCrudEntityEvent
{
    /**
     * @var Post
     */
    protected $entity;

    private $webPath;

    private $oldCover;

    public function __construct($projectDir)
    {
        $this->webPath = $projectDir . '/web';
        $this->oldCover = StartEditPostEvent::$oldProperty['cover'];
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
