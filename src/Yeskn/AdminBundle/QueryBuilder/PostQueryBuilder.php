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
        'title', 'author', 'createdAt'
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

        if (!empty($params['createdAt'])) {
            $this->queryBuilder
                ->andWhere('p.createdAt >= :createdAt')
                ->setParameter('createdAt', $params['createdAt']);
        }

        return $this->queryBuilder;
    }
}
