<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-18 20:00:18
 */

namespace Yeskn\AdminBundle\CrudEvent;

interface CrudListEventInterface extends CrudEventInterface
{
    public function setList(array $list);

    public function execute();
}
