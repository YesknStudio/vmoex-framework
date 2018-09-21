<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-20 14:00:26
 */

namespace Yeskn\Support\File;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

class UploadFileHandler
{
    private $basePath;

    public function __construct($projectDir)
    {
        $this->basePath = $projectDir . '/web';
    }

    public function upload($entity, $attribute)
    {
        $getMethod = 'get' . ucfirst($attribute);

        if (!method_exists($entity, $getMethod)) {
            throw new \Exception();
        }

        /** @var File $file */
        $file = $entity->$getMethod();

        $extension = $file->guessExtension();
        $fileName = '/upload/' . time() . mt_rand(1000, 9999) . '.' . $extension;

        $targetPath = $this->basePath . $fileName;

        $fs = new Filesystem();
        $fs->copy($file->getRealPath(), $targetPath);

        $method = 'set' . ucfirst($attribute);

        if (method_exists($entity, $method)) {
            $entity->$method($fileName);

            return new UploadedFile($targetPath);
        }

        throw new \Exception();
    }
}