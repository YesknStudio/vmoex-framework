<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2019-03-30 14:28:48
 */

namespace Yeskn\AdminBundle\QueryBuilder;

use Doctrine\ORM\QueryBuilder;

class DefaultQueryBuilder
{
    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * @var array
     */
    protected $params;

    protected $keys;

    protected $reservedKeys;

    public function __construct($queryBuilder, $params)
    {
        $this->queryBuilder = $queryBuilder;
        $this->queryBuilder->where('1=1');

        $this->params = $params;

        unset($params['pageNo'], $params['pageSize']);

        $reservedParams = [];

        if (!empty($this->reservedKeys)) {
            foreach ($params as $key => $param) {
                if (in_array($key, $this->reservedKeys)) {
                    $reservedParams[$key] = $param;
                    unset($params[$key]);
                }
            }
        }

        foreach ($params as $key => $param) {
            if (!empty($param)) {
                $this->queryBuilder->andWhere(sprintf('p.%s = :%s', $key, $key))
                    ->setParameter($key, $param);
            }
        }

        $this->reservedQuery($reservedParams);
    }

    public function reservedQuery(array $params)
    {
        return $this->queryBuilder;
    }

    public function getList()
    {
        $params = $this->params;

        $queryBuilder = clone $this->queryBuilder;

        if (!empty($params['pageSize'])) {
            $queryBuilder->setMaxResults($params['pageSize']);
        }

        if (!empty($params['pageNo'])) {
            $queryBuilder->setFirstResult(($params['pageNo'] - 1) * $params['pageSize']);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function getTotal()
    {
        $queryBuilder = clone $this->queryBuilder;
        return (int)$queryBuilder->select('COUNT(p)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
