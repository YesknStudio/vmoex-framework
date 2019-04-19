<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-20 14:00:26
 */

namespace Yeskn\Support\File;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Yeskn\Support\ParameterBag;

class UploadFileHandler
{
    private $basePath;

    protected $fileName;

    /**
     * @param File $file
     * @throws \Symfony\Component\Filesystem\Exception\FileNotFoundException
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     * @return string
     */
    private function save($file)
    {
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

        return $targetPath;
    }

    public function __construct($projectDir)
    {
        $this->basePath = $projectDir . '/web';
    }

    public function upload($entity, $attribute)
    {
        if ($entity instanceof ParameterBag) {
            return $this->uploadToParameterBag($entity, $attribute);
        }

        $getMethod = 'get' . ucfirst($attribute);

        if (!method_exists($entity, $getMethod)) {
            throw new \Exception();
        }

        /** @var File $file */
        $file = $entity->$getMethod();

        $targetPath = $this->save($file);

        $method = 'set' . ucfirst($attribute);

        if (method_exists($entity, $method)) {
            $entity->$method($this->fileName);

            return new UploadedFile($targetPath);
        }

        throw new \Exception();
    }

    /**
     * @param ParameterBag $entity
     * @param $attribute
     * @return UploadedFile
     */
    public function uploadToParameterBag($entity, $attribute)
    {
        $file = $entity->get($attribute);

        $target = $this->save($file);

        $entity->set($attribute, $this->fileName);

        return new UploadedFile($target);
    }
}
