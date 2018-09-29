<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jaggle
 * Create: 2018-07-04 22:15:01
 */

namespace Yeskn\MainBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Yeskn\MainBundle\Entity\Blog;

class AllocateSpaceService
{
    private $container;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    private $blog;

    public function __construct(ContainerInterface $container, EntityManagerInterface $entityManager)
    {
        $this->container = $container;
        $this->em = $entityManager;
    }
    public function allocate($name, $size, $imgPath, $mountPath, $type = 'web')
    {
        $fs = new Filesystem();

        $mount = $mountPath . '/' . $name; // /alidata/www/wpcraft-blogs/xxx
        $img = $imgPath . '/' . $name . '.img';  // /mnt/wpcast-web/xxx.img

        if (!$fs->exists($mount)) {
            $fs->mkdir($mount);
        }

        $process = new Process("dd if=/dev/zero of={$img} bs=1M count={$size}");

        $process->run();

        $deviceRepo = $this->em->getRepository('YesknMainBundle:Device');

        $device = $deviceRepo->findOneBy(['blog' => null], ['id' => 'ASC']);

        if (!$device) {
            throw new \Exception('no more device');
        }

        $deviceName = $device->getDeviceName();

        $device->setBlog($this->blog);
        $device->setType($type);

        $this->em->flush();

        $process->setCommandLine("losetup {$deviceName} {$img}")->run();
        $process->setCommandLine("mkfs.ext3 {$deviceName}")->run();
        $process->setCommandLine("losetup -d {$deviceName}")->run();
        $process->setCommandLine("mount -o loop {$img} {$mount}")->run();

        return $mount;
    }

    public function allocateWebSpace($name)
    {
        $webConfig = $this->container->getParameter('wpcast');

        return $this->allocate($name,
            $webConfig['web_size'],
            $webConfig['web_img_path'],
            $webConfig['web_path'],
            'web'
        );
    }

    public function allocateDbSpace($name)
    {
        $webConfig = $this->container->getParameter('wpcast');

        return $this->allocate('wpcast_'.$name,
            $webConfig['db_size'],
            $webConfig['db_img_path'],
            $webConfig['db_path'],
        'db'
        );
    }

    public function setBlog(Blog $blog)
    {
        $this->blog = $blog;
    }
}