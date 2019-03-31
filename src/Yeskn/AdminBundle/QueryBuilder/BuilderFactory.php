<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2019-03-30 17:41:17
 */

namespace Yeskn\AdminBundle\QueryBuilder;

class BuilderFactory
{
    /**
     * @param $name
     * @param $builder
     * @param $queryParams
     * @return DefaultQueryBuilder
     */
    public static function createQueryBuilder($name, $builder, $queryParams)
    {
        $builderClass = "Yeskn\AdminBundle\QueryBuilder\\{$name}QueryBuilder";

        if (class_exists($builderClass)) {
            return new $builderClass($builder, $queryParams);
        } else {
            return new DefaultQueryBuilder($builder, $queryParams);
        }
    }
}
