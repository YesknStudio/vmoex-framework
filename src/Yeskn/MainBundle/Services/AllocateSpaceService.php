<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jaggle
 * Create: 2018-07-04 22:15:01
 */

namespace Yeskn\MainBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class AllocateSpaceService
{

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    public function allocate($name, $size, $imgPath, $mountPath)
    {
        $fs = new Filesystem();

        $mount = $mountPath . '/' . $name;
        $img = $imgPath . '/' . $name . '.img';

        if (!$fs->exists($mount)) {
            $fs->mkdir($mount);
        }

        $process = new Process("dd if=/dev/zero of={$img} bs=1M count={$size}");

        $process->run();

        $process->setCommandLine("losetup {$img}")->run();
        $process->setCommandLine('mkfs.ext4 /dev/loop0')->run();
        $process->setCommandLine("mount {$img} {$mount}")->run();
        $process->setCommandLine('losetup -d /dev/loop0')->run();

        return $mount;
    }

    public function allocateWebSpace($name)
    {
        $webConfig = $this->container->getParameter('wpcast');

        return $this->allocate($name,
            $webConfig['web_size'],
            $webConfig['web_img_path'],
            $webConfig['web_path']);
    }

    public function allocateDbSpace($name)
    {
        $webConfig = $this->container->getParameter('wpcast');

        return $this->allocate('wpcast_'.$name,
            $webConfig['db_size'],
            $webConfig['db_img_path'],
            $webConfig['db_path']);
    }
}