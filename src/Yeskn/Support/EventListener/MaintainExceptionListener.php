<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2019-03-27 22:46:42
 */

namespace Yeskn\Support\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Yeskn\Support\Exceptions\AccessDeniedHttpException;

class MaintainExceptionListener
{
    private $templating;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if ($event->getException() instanceof AccessDeniedHttpException) {
            $event->setResponse(new Response(
                $this->templating->render('@YesknSupport/maintain.html.twig', [
                'message' => $event->getException()->getMessage()
            ])));
        }
    }
}
