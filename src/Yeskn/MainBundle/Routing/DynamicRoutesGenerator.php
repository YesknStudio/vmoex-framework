<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jaggle
 * Create: 2018-12-20 22:38:23
 */

namespace Yeskn\MainBundle\Routing;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Yeskn\MainBundle\Entity\Page;
use Yeskn\Support\Routing\DynamicRoutesGeneratorInterface;

class DynamicRoutesGenerator implements DynamicRoutesGeneratorInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return RouteCollection
     * @throws \UnexpectedValueException
     */
    public function generateRoutes()
    {
        $routes = new RouteCollection();

        $pages = $this->em->getRepository(Page::class)->findBy(['status' => 1]);

        $defaults = array(
            '_controller' => 'YesknMainBundle:Page:render',
        );

        foreach ($pages as $page) {
            $route = new Route($page->getUri(), $defaults, []);
            $routeName = 'extraRoute_' . md5($page->getId());
            $routes->add($routeName, $route);
        }

        return $routes;
    }
}
