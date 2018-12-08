<?php

/**
 * This file is part of project project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-14 17:34:28
 */

namespace Yeskn\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends Controller
{
    /**
     *
     * @Route("/search")
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function searchAction(Request $request)
    {
        $s = strip_tags($request->get('s'));
        $count = $request->get('c');

        $em = $this->getDoctrine()->getManager();

        $postResults = $em->getRepository('YesknMainBundle:Post')->queryPosts($s, $count);

        $userResults = $em->getRepository('YesknMainBundle:User')->queryUser($s, $count);

        return $this->render('@YesknMain/search/search.html.twig', [
            'word' => $s,
            'posts' => $postResults,
            'users' => $userResults
        ]);
    }
}
