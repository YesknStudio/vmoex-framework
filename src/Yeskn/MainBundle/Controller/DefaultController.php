<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jaggle
 * Create: 2018-09-12 14:30:12
 */

namespace Yeskn\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Yeskn\MainBundle\Entity\Blog;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $tab = $request->get('tab');
        $page = $request->get('page', 1);

        $blogRepo = $this->getDoctrine()->getRepository('YesknMainBundle:Blog');

        $blogList = $blogRepo->findBy(['status' => Blog::STATUS_CREATED], ['createdAt' => 'DESC']);

        return $this->forward('YesknMainBundle:Common:homeList', [
            'tab' => $tab,
            'page' => $page,
            'scope' => 'home',
            'blogList' => $blogList,
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
        return new Response();
    }
}
