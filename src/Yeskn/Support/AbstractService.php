<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
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
