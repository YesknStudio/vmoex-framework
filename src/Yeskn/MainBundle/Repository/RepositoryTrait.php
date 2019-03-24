<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2019-03-24 22:21:15
 */

namespace Yeskn\MainBundle\Repository;

use Doctrine\ORM\QueryBuilder;

trait RepositoryTrait
{
    public function total()
    {
        /**
         * @var QueryBuilder $builder
         */
        $builder = $this->createQueryBuilder('p');
        $builder->select('COUNT(1)');
        return (int)$builder->getQuery()->getSingleScalarResult();
    }
}
