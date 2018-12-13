<?php

/**
 * This file is part of project project yeskn-studio/vmoex-framework.
 *
 * Author: Jaggle
 * Create: 2018-09-12 14:30:12
 */

namespace Yeskn\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $tab = $request->get('tab');
        $page = $request->get('page', 1);
        return $this->forward('YesknMainBundle:Common:homeList', [
            'tab' => $tab,
            'page' => $page,
            'scope' => 'home',
            'sortBy' => $request->get('sortBy', 'com')
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function aboutAction()
    {
        return $this->render('@YesknMain/about.html.twig');
    }

    /**
     * @Route("/contribute", name="contribute")
     */
    public function contributeAction()
    {
        return $this->render('@YesknMain/contribute.html.twig');
    }
}
