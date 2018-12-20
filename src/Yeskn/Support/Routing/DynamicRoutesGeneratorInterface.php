<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-12-20 22:33:39
 */

namespace Yeskn\Support\Routing;

use Symfony\Component\Routing\RouteCollection;

interface DynamicRoutesGeneratorInterface
{
    /**
     * @return RouteCollection
     */
    public function generateRoutes();
}
