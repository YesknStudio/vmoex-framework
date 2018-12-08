<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-23 15:27:12
 */

namespace Yeskn\Support\Http;

use Symfony\Component\HttpFoundation\Request;

/**
 * Trait HttpError
 * @package Yeskn\Support\Http
 */
trait HttpResponse
{
    public function errorResponse($msg)
    {
        $isXhr = $this->get('request_stack')->getCurrentRequest()->isXmlHttpRequest();

        if ($isXhr) {
            return new ApiFail($msg);
        }

        $this->addFlash('danger', $msg);

        /** @var Request $request */
        $request = $this->get('request_stack')->getCurrentRequest();

        return $this->redirect($request->headers->get('referer'));
    }
}
