<?php

namespace Yeskn\MainBundle\Form\DataTransfer;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Intervention\Image\ImageManagerStatic as Image;

class StringToImageTransformer implements DataTransformerInterface
{
    private $webRoot;

    private $dir = 'upload';

    public function __construct($webRoot)
    {
        $this->webRoot = $webRoot .'/web';
    }

    /**
     * @param mixed $value
     * @return mixed|File
     */
    public function transform($value)
    {
        $filePath = $this->webRoot . '/' . $this->getDir() . '/' . $value;

        if (is_file($filePath)) {
            return new File($filePath);
        }

        return new File($value, false);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return string
     */
    public function reverseTransform($file)
    {
        if (is_null($file)) {
            return false;
        }

        $extension = $file->guessExtension();
        $fileName = time() . mt_rand(1000, 9999) . '.' . $extension;
        $targetPath = $this->webRoot . '/' . $this->getDir() .  '/' . $fileName;

        $fs = new Filesystem();
        $fs->copy($file->getRealPath(), $targetPath);

        Image::configure(array('driver' => 'gd'));

        $image = Image::make($targetPath);
        $image->resize(100, 100)->save();

        return $this->getDir() . '/' . $fileName;
    }

    /**
     * @return mixed
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * @param mixed $dir
     */
    public function setDir($dir)
    {
        $this->dir = $dir;
    }
}