<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jaggle
 * Create: 2018-09-14 17:21:46
 */

namespace Yeskn\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LocaleController extends Controller
{
    /**
     * @Route("/set-locale", name="set_locale", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function setLocale(Request $request)
    {
        $locale = $request->get('locale');

        if (in_array($locale, ['en', 'zh_CN', 'zh_TW', 'jp'])) {
            $response = new JsonResponse(['ret' => 1, 'msg' => $this->get('translator')->trans('success')]);
            $response->headers->setCookie(new Cookie('_locale', $locale));

            return $response;
        }

        return new JsonResponse(['ret' => 0, 'msg' => 'locale invalid']);
    }
}