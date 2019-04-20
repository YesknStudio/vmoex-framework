<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2019-03-30 14:28:37
 */

namespace Yeskn\AdminBundle\QueryBuilder;

class VisitQueryBuilder extends DefaultQueryBuilder
{
    protected $reservedKeys = [
     'createdAt'
    ];

    public function reservedQuery(array $params)
    {
        if (!empty($params['createdAt'][0])) {
            $this->queryBuilder
                ->andWhere('p.createdAt >= :createdAt0')->setParameter('createdAt0', $params['createdAt'][0])
                ->andWhere('p.createdAt <= :createdAt1')->setParameter('createdAt1', $params['createdAt'][1])
            ;
        }

        return $this->queryBuilder;
    }
}
