<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-16 10:54:50
 */

namespace Yeskn\Support\Http\Session;

use Psr\Container\ContainerInterface;

/**
 * Trait Flash
 * @package Yeskn\Support\Http\Session
 *
 * @property  ContainerInterface $container
 */
trait Flash
{
    public function addSuccessFlash($type = 'success', $message = '操作成功')
    {

        if (!$this->container->get('session')) {
            throw new \LogicException('You can not use the addFlash method if sessions are disabled. Enable them in "config/packages/framework.yaml".');
        }

        $this->container->get('session')->getFlashBag()->add($type, $message);
    }
}