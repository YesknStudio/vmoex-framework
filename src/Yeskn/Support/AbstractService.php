<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-10-18 22:41:09
 */

namespace Yeskn\Support;

use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractService
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
}
