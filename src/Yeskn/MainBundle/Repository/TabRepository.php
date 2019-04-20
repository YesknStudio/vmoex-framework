<?php

/**
 * This file is part of project project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-14 18:19:28
 */

namespace Yeskn\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Yeskn\MainBundle\Entity\Tab;

class TabRepository extends EntityRepository
{
    use RepositoryTrait;

    /**
     * @return Tab[]
     */
    public function getTabsForWidget()
    {
        return $this->createQueryBuilder('p')
            ->where('p.avatar is not null')
            ->andWhere("p.avatar != ''")
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(12)
            ->getQuery()
            ->getResult(Query::HYDRATE_SIMPLEOBJECT);
    }
}
