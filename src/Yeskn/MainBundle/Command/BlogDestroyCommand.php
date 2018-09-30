<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-30 20:09:07
 */

namespace Yeskn\MainBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Yeskn\MainBundle\Entity\Device;
use Yeskn\Support\Command\AbstractCommand;

class BlogDestroyCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('blog:destroy');
        $this->addOption('blog', null, InputOption::VALUE_REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->doctrine();

        $blogName = $input->getOption('blog');

        $blog = $doctrine->getRepository('YesknMainBundle:Blog')->findOneBy([
            'subdomain' => $blogName
        ]);

        $command = new Process('date');

        if ($blog) {
            /** @var Device[] $devices */
            $devices = $blog->getDevices();

            foreach ($devices as $device) {
                $device->setBlog(null);
                $device->setType('');

                if ($deviceName = $device->getDeviceName()) {
                    $command->setCommandLine( "losetup -d {$deviceName}")->run();
                    $command->setCommandLine( "umount {$deviceName}")->run();
                }
            }

            $this->em()->remove($blog);
            $this->em()->flush();
        }

        $this->connection()->executeQuery("drop database wpcast_{$blogName}");

        $fs = new Filesystem();

        $config = $this->parameter('wpcast');

        $webPath = $config['web_path'] . '/' . $blogName;
        $webImg = $config['web_img_path'] . '/' . $blogName . '.img';

        $dbPath = $config['db_path'] . '/wpcast_' . $blogName;
        $dbImg = $config['db_img_path'] . '/wpcast_' . $blogName . '.img';

        $fs->remove($webPath);
        $fs->remove($webImg);
        $fs->remove($dbPath);
        $fs->remove($dbImg);

        $output->writeln('<comment>finished!</comment>');
    }
}