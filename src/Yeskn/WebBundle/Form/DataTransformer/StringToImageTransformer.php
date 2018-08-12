<?php
/**
 * This file is part of project Vmoex.
 *
 * Author: Jake
 * Create: 2018-06-18 19:53:39
 */

namespace Yeskn\WebBundle\Form\DataTransformer;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Intervention\Image\ImageManagerStatic as Image;

class StringToImageTransformer implements DataTransformerInterface
{
    private $container;
    private $pathPrefix = '/';

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

    }

    /**
     * @param mixed $value
     * @return mixed|File
     */
    public function transform($value)
    {
        return $value ? new File($this->container->getParameter('kernel.project_dir') . '/web/' . $value) : null;
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
        $targetPath = $this->container->getParameter('kernel.project_dir') . '/web/' . $this->getPathPrefix() . $fileName;

        $fs = new Filesystem();
        $fs->copy($file->getRealPath(), $targetPath);

        Image::configure(array('driver' => 'gd'));

        $image = Image::make($targetPath);
        $image->resize(100, 100)->save();

        return $this->getPathPrefix() . $fileName;
    }

    /**
     * @return mixed
     */
    public function getPathPrefix()
    {
        return $this->pathPrefix;
    }

    /**
     * @param mixed $pathPrefix
     */
    public function setPathPrefix($pathPrefix)
    {
        $this->pathPrefix = $pathPrefix;
    }
}