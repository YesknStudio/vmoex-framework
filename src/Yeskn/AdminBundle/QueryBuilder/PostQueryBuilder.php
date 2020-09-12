<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2019-03-30 14:28:37
 */

namespace Yeskn\AdminBundle\QueryBuilder;

class PostQueryBuilder extends DefaultQueryBuilder
{
    protected $reservedKeys = [
        'title', 'author', 'createdAt', 'tabName'
    ];

    public function reservedQuery(array $params)
    {
        if (!empty($params['title'])) {
            $this->queryBuilder->andWhere('p.title LIKE :title');
            $this->queryBuilder->setParameter('title', "%{$params['title']}%");
        }

        if (!empty($params['author'])) {
            $this->queryBuilder->leftJoin('p.author', 'u')
                ->andWhere('u.nickname LIKE :author')
                ->setParameter('author', "%{$params['author']}%");
        }

        if (!empty($params['createdAt'][0])) {
            $this->queryBuilder
                ->andWhere('p.createdAt >= :createdAt0 and p.createdAt <= :createdAt1')
                ->setParameter('createdAt0', $params['createdAt'][0])
                ->setParameter('createdAt1', $params['createdAt'][1]);
        }

        if (!empty($params['tabName'])) {
            $this->queryBuilder->leftJoin('p.tab', 't')
                ->andWhere('t.name LIKE :tab')
                ->setParameter('tab', "%{$params['tabName']}%");
        }

        return $this->queryBuilder;
    }
}
