<?php
/**
 * This file is part of project Vmoex.
 *
 * Author: Jake
 * Create: 2018-05-31 21:18:20
 */

namespace Yeskn\BlogBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ResponseListener
{
    private $container;

    public function __construct()
    {
    }


    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();

        $response->headers->remove('X-Powered-By');
        $response->headers->set('X-Powered-By', 'Vmoex/0.1.12');
    }
}