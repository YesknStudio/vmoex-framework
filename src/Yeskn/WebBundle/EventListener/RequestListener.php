<?php
/**
 * This file is part of project Vmoex.
 *
 * Author: Jake
 * Create: 2018-06-09 20:14:51
 */

namespace Yeskn\WebBundle\EventListener;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RequestListener
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        $defaultLocale = 'zh_CN';

        $locale = $event->getRequest()->query->get('_locale');

        if (empty($locale)) {
            $locale = $event->getRequest()->cookies->get('_locale');
        }

        if (empty($locale)) {
            $locale = $defaultLocale;
        }

        if (!in_array($locale, ['en', 'zh_CN', 'jp', 'zh_TW'])) {
            $locale = $defaultLocale;
        }

        $event->getRequest()->setLocale($locale);
    }
}