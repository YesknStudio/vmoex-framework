<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-20 14:15:09
 */

namespace Yeskn\Support\File;

use Intervention\Image\ImageManagerStatic as Image;

/**
 * Class ImageHandler
 * @package Yeskn\Support\File
 *
 * only support native file currently.
 */
class ImageHandler extends UploadFileHandler
{
    private $width;

    private $height;

    public function handle($entity, $attribute)
    {
        $uploaded = $this->upload($entity, $attribute);

        Image::configure(array('driver' => 'gd'));
        $image = Image::make($uploaded->getAbsoluteFileName());
        $image->resize($this->width, $this->height)->save();
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param mixed $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param mixed $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }
}