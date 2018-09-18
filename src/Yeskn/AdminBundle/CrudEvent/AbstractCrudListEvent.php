<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-18 20:33:48
 */

namespace Yeskn\AdminBundle\CrudEvent;

abstract class AbstractCrudListEvent implements CrudListEventInterface
{
    protected $list;

    public function setList(array $list)
    {
        $this->list = $list;

        return $this;
    }

    public function getList()
    {
        return $this->list;
    }

    abstract function execute();
}