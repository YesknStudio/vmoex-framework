<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-17 00:07:18
 */

namespace Yeskn\MainBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Yeskn\Support\Http\ApiFail;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        if ($request->isXmlHttpRequest()) {
            return new ApiFail($this->translator->trans('access_denied'));
        }

        return $accessDeniedException;
    }
}
