<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-30 20:09:48
 */

namespace Yeskn\Support\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Yeskn\Support\Traits\OptionsTrait;

abstract class AbstractCommand extends ContainerAwareCommand
{
    use OptionsTrait;

    protected $input;
    protected $output;

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry|object
     * @throws \LogicException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function doctrine()
    {
        return $this->getContainer()->get('doctrine');
    }

    /**
     * alias for $this->doctrine()
     */
    public function getDoctrine()
    {
        return $this->doctrine();
    }

    /**
     * @return \Doctrine\ORM\EntityManager|object
     * @throws \LogicException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function em()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @param $paramName
     * @return mixed
     * @throws \LogicException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    public function parameter($paramName)
    {
        return $this->getContainer()->getParameter($paramName);
    }

    /**
     * @return \Doctrine\DBAL\Connection|object
     * @throws \LogicException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function connection()
    {
        return $this->getContainer()->get('database_connection');
    }

    /**
     * @param $service
     * @return object
     * @throws \LogicException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function get($service)
    {
        return $this->getContainer()->get($service);
    }

    /**
     * @param $name
     * @throws \LogicException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function repo($name)
    {
        $this->doctrine()->getRepository($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * @return SymfonyStyle
     */
    public function io()
    {
        $io = new SymfonyStyle($this->input, $this->output);
        return $io;
    }
}
