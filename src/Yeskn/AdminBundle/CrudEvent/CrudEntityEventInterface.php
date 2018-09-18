<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-18 20:03:38
 */

namespace Yeskn\AdminBundle\CrudEvent;

interface CrudEntityEventInterface extends CrudEventInterface
{
    public function setEntity($entity);
    public function execute();
}