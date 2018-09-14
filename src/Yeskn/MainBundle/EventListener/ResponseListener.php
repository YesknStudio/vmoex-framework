<?php
/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jake
 * Create: 2018-05-31 21:18:20
 */

namespace Yeskn\MainBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ResponseListener
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();

        $response->headers->remove('X-Powered-By');
        $response->headers->set('X-Powered-By', 'Vmoex/0.1.12');

//        $locale = $event->getRequest()->cookies->get('_locale');
//
//        if (empty($locale)) {
//            $locale = 'zh_CN';
//        }
//
//        $response->headers->setCookie(new Cookie('_locale', $locale));
    }
}