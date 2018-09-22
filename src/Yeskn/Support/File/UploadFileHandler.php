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

    protected $fileName;

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

        if (empty($this->fileName)) {
            $extension = $file->guessExtension();
            $this->fileName = '/upload/' . time() . mt_rand(1000, 9999) . '.' . $extension;
        } else {
            if (strpos($this->fileName, '/') !== 0) {
                $this->fileName = '/upload/' . $this->fileName;
            }
        }

        $targetPath = $this->basePath . $this->fileName;

        $fs = new Filesystem();
        $fs->copy($file->getRealPath(), $targetPath);

        $method = 'set' . ucfirst($attribute);

        if (method_exists($entity, $method)) {
            $entity->$method($this->fileName);

            return new UploadedFile($targetPath);
        }

        throw new \Exception();
    }
}