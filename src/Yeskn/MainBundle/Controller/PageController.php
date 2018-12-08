<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-18 00:25:12
 */

namespace Yeskn\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Yeskn\MainBundle\Entity\Page;

class PageController extends Controller
{
    public function renderAction(Request $request)
    {
        $uri = $request->getPathInfo();

        $one = $this->getDoctrine()->getRepository(Page::class)
            ->findOneBy(['uri' => $uri]);

        return $this->render('@YesknMain/page/default.html.twig', [
            'pageName' => $one->getTitle(),
            'content' => $one->getContent()
        ]);
    }
}
