<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-17 14:37:50
 */

namespace Yeskn\Support\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Yeskn\Support\Http\ApiFail;

class ExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $isAjax = $event->getRequest()->isXmlHttpRequest();
        $event->allowCustomResponseCode();

        if ($isAjax) {
            $response = new ApiFail($exception->getMessage());
            $response->setStatusCode(200);

            $event->setResponse($response);
        }

        return $exception;
    }
}