<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-18 20:08:23
 */

namespace Yeskn\AdminBundle\CrudEvent;

abstract class AbstractCrudEntityEvent implements CrudEntityEventInterface
{
    protected $entity;

    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    abstract function execute();
}
