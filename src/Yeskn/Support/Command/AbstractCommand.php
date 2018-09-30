<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-30 20:09:48
 */

namespace Yeskn\Support\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

abstract class AbstractCommand extends ContainerAwareCommand
{
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
}