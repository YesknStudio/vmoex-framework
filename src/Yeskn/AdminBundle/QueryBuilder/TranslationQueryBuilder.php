<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2019-03-30 14:28:37
 */

namespace Yeskn\AdminBundle\QueryBuilder;

class TranslationQueryBuilder extends DefaultQueryBuilder
{
    protected $reservedKeys = [
        'messageId', 'chinese', 'japanese', 'taiwanese'
    ];

    public function reservedQuery(array $params)
    {
        if (!empty($params['messageId'])) {
            $this->queryBuilder->andWhere('p.messageId LIKE :messageId');
            $this->queryBuilder->setParameter('messageId', "%{$params['messageId']}%");
        }

        if (!empty($params['chinese'])) {
            $this->queryBuilder->andWhere('p.chinese LIKE :chinese');
            $this->queryBuilder->setParameter('chinese', "%{$params['chinese']}%");
        }

        if (!empty($params['japanese'])) {
            $this->queryBuilder->andWhere('p.japanese LIKE :japanese');
            $this->queryBuilder->setParameter('japanese', "%{$params['japanese']}%");
        }

        if (!empty($params['taiwanese'])) {
            $this->queryBuilder->andWhere('p.taiwanese LIKE :taiwanese');
            $this->queryBuilder->setParameter('taiwanese', "%{$params['taiwanese']}%");
        }

        return $this->queryBuilder;
    }
}
