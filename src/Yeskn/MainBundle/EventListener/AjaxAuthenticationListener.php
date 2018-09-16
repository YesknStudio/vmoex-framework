<?php

namespace Yeskn\MainBundle\EventListener;

use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Yeskn\Support\Http\ApiFail;

class AjaxAuthenticationListener
{
    /**
     * Handles security related exceptions.
     *
     * @param GetResponseForExceptionEvent $event An GetResponseForExceptionEvent instance
     */
    public function onCoreException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $request = $event->getRequest();

        if ($request->isXmlHttpRequest()) {
            if ($exception instanceof AuthenticationException
                || $exception instanceof AccessDeniedException
                || $exception instanceof AuthenticationCredentialsNotFoundException
            ) {
                $response = new ApiFail('please login');
                $response->setStatusCode(200);

                $event->allowCustomResponseCode();
                $event->setResponse($response);
            }
        }
    }
}