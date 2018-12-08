<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-18 00:41:31
 */

namespace Yeskn\MainBundle\Routing;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Yeskn\MainBundle\Entity\Page;

class DynamicLoader extends Loader
{
    private $em;
    private $isLoaded = false;


    public function __construct(EntityManagerInterface $manager)
    {
        $this->em = $manager;
    }

    public function load($resource, $type = null)
    {
        $routes = new RouteCollection();

        $defaults = array(
            '_controller' => 'YesknMainBundle:Page:render',
        );

        $pages = $this->em->getRepository(Page::class)->findBy(['status' => 1]);

        foreach ($pages as $page) {
            $route = new Route($page->getUri(), $defaults, []);
            $routeName = 'extraRoute_' . md5($page->getId());
            $routes->add($routeName, $route);
        }

        $this->isLoaded = true;

        return $routes;
    }

    public function supports($resource, $type = null)
    {
        return 'extra' === $type;
    }
}
