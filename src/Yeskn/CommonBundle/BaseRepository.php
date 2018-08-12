<?php
/**
 * This file is part of project vmoex.
 *
 * Author: Jaggle
 * Create: 2018-08-12 12:59:15
 */

namespace Yeskn\CommonBundle;

use Doctrine\ORM\EntityRepository;
use Yeskn\CommonBundle\Utils\Common\PageData;

class BaseRepository extends EntityRepository
{
    public $pageSize = 20;

    public function getPageData($page = 1)
    {
        $page = $page ?: 1;
        $offset = ($page - 1) * $this->pageSize;

        $queryBuilder = $this->createQueryBuilder('p');

        $count = (clone $queryBuilder)->select('count(p)')->getQuery()->getSingleScalarResult();
        $list = $queryBuilder->setFirstResult($offset)->setMaxResults($this->pageSize)->getQuery()->getResult();

        return new PageData($list, $count);
    }
}