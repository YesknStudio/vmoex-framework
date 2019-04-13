<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2019-04-13 09:03:09
 */

namespace Yeskn\AdminBundle\QueryBuilder;

class CommentQueryBuilder extends DefaultQueryBuilder
{
    protected $reservedKeys = [
        'content', 'title', 'createdBy', 'createdAt'
    ];

    public function reservedQuery(array $params)
    {
        if (!empty($params['content'])) {
            $this->queryBuilder->andWhere('p.content LIKE :content');
            $this->queryBuilder->setParameter('content', "%{$params['content']}%");
        }

        if (!empty($params['createdBy'])) {
            $this->queryBuilder->leftJoin('p.user', 'u');
            $this->queryBuilder->andWhere('u.nickname LIKE :nickname');
            $this->queryBuilder->setParameter('nickname', "%{$params['createdBy']}%");
        }

        if (!empty($params['title'])) {
            $this->queryBuilder->leftJoin('p.post', 'po');
            $this->queryBuilder->andWhere('po.title LIKE :title');
            $this->queryBuilder->setParameter('title', "%{$params['title']}%");
        }

        if (!empty($params['createdAt'])) {
            $this->queryBuilder
                ->andWhere('p.createdAt >= :createdAt0 and p.createdAt <= :createdAt1')
                ->setParameter('createdAt0', $params['createdAt'][0])
                ->setParameter('createdAt1', $params['createdAt'][1]);
        }

        return $this->queryBuilder;
    }
}
