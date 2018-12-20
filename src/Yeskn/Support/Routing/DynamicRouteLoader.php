<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jaggle
 * Create: 2018-09-18 00:41:31
 */

namespace Yeskn\Support\Routing;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Loader\Loader;

class DynamicRouteLoader extends Loader
{
    private $em;
    private $isLoaded = false;
    private $dynamicRoute;

    public function __construct(EntityManagerInterface $manager, DynamicRoutesGeneratorInterface $dynamicRoute)
    {
        $this->em = $manager;
        $this->dynamicRoute = $dynamicRoute;
    }

    public function load($resource, $type = null)
    {
        $routes = $this->dynamicRoute->generateRoutes();

        $this->isLoaded = true;

        return $routes;
    }

    public function supports($resource, $type = null)
    {
        return 'extra' === $type;
    }
}
