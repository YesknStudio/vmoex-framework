<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-29 20:00:09
 */

namespace Yeskn\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Yeskn\MainBundle\Entity\Device;

class AddDeviceCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('device:add');
        $this->addOption('start-index', null, InputOption::VALUE_REQUIRED);
        $this->addOption('amount', null, InputOption::VALUE_REQUIRED, 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $index = $input->getOption('start-index');
        $amount = $input->getOption('amount');

        $doctrine = $this->getContainer()->get('doctrine');

        $em = $doctrine->getManager();

        $machineRepo = $doctrine->getRepository('YesknMainBundle:Machine');

        $machine = $machineRepo->find(1);

        for ($i = $index; $i < $index + $amount; $i++) {
            $deviceName = "/dev/loop{$i}";

            $device = new Device();

            $device->setDeviceName($deviceName);
            $device->setMachine($machine);

            $em->persist($device);
            $em->flush();

            $process = new Process("mknod -m 0660 {$deviceName} b 7 {$i}");
            $code = $process->run();

            if ($code != 0) {
                $output->writeln("<error>{$i} got error: {$code}</error>");
                return ;
            }
        }

        $output->writeln("<info>finished!</info>");


    }


}