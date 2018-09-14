<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-14 18:20:44
 */

namespace Yeskn\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller
{
    /**
     * @Route("/", defaults={"page": 1},name="post_list")
     * @Route("/tab/{tab}", defaults={"page":1}, name="posts_with_tab")
     * @Route("/page/{page}", requirements={"page": "[1-9]\d*"}, name="blog_doc_paginated")
     * 
     * @param $request
     * @return Response|JsonResponse
     */
    public function homeAction(Request $request, $page)
    {
        $tab = $request->attributes->get('tab');
        $pagesize = 25;

        if (empty($tab)) {
            $tab = $request->query->get('tab');
        }

        if (empty($tab)) {
            $tab = $request->cookies->get('_tab');
        }

        if (empty($tab)) {
            $tab = 'all';
        }

        $sort = ['updatedAt' => 'DESC'];

        if ($tab == 'hot') {
            $sort = ['views' => 'DESC'];
        }

        $tabObj = null;

        if ($tab and $tab != 'all' and $tab != 'hot') {
            $tabObj = $this->getDoctrine()->getRepository('YesknMainBundle:Tab')
                ->findOneBy(['alias' => $tab]);
            if (empty($tabObj)) {
                return new JsonResponse('tab not exists');
            }
        }

        $posts = $this->getDoctrine()->getRepository('YesknMainBundle:Post')
            ->getIndexList($sort, $pagesize, $pagesize*($page-1));

        $countQuery = $this->getDoctrine()->getRepository('YesknMainBundle:Post')
            ->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.isDeleted = false');

        if (!empty($tabObj)) {
            $countQuery->andWhere('a.tab = :tab')->setParameter('tab', $tabObj);

        }

        $count = $countQuery->getQuery()->getSingleScalarResult();

        $allTabs = $this->getDoctrine()->getRepository('YesknMainBundle:Tab')
            ->findBy(['level' => 1]);

        $pageData['allPage'] = ceil($count/$pagesize);
        $pageData['currentPage'] = $page;

        $response = $this->render('@YesknAdmin/post/index.html.twig', array(
            'posts' => $posts,
            'tab' => $tab,
            'currentTab' => $tabObj,
            'tabs' => $allTabs,
            'pageData' => $pageData
        ));

        if ($tabObj and $tabObj->getLevel() == 1) {
            $response->headers->setCookie(new Cookie('_tab', $tab));
        }

        if ($tab == 'all' or $tab == 'hot') {
            $response->headers->setCookie(new Cookie('_tab', $tab));
        }

        return $response;
    }
}