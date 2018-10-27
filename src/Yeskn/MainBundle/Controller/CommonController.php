<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-22 11:36:13
 */

namespace Yeskn\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommonController extends Controller
{
    public function homeListAction(Request $request)
    {
        $tab = $request->get('tab');
        $page = $request->get('page', 1);
        $scope = $request->get('scope');
        $blogList = $request->get('blogList');
        $sortBy = $request->get('sortBy');

        $pagesize = 25;

        if (empty($tab)) {
            $tab = $request->cookies->get('_tab');
        }

        if (empty($tab)) {
            $tab = 'all';
        }

        if ($sortBy == 'pub') {
            $sort = ['createdAt' => 'DESC'];
        } else {
            $sort = ['updatedAt' => 'DESC'];
        }

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
            ->getIndexList($tabObj, $sort, $pagesize, $pagesize*($page-1));

        $countQuery = $this->getDoctrine()->getRepository('YesknMainBundle:Post')
            ->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.isDeleted = false');

        if (!empty($tabObj)) {
            if ($tabObj->getLevel() == 1) {
                $subItems = $this->get('doctrine.orm.entity_manager')->getRepository('YesknMainBundle:Tab')
                    ->createQueryBuilder('t')
                    ->select('t.id')
                    ->where('t.parent = :parent')
                    ->andWhere('t.level = 2')
                    ->setParameter('parent', $tab)
                    ->getQuery()
                    ->getArrayResult();

                $subIds = array_column($subItems, 'id') + [$tabObj->getId()];

                $countQuery->orWhere($countQuery->expr()->in('a.tab', $subIds));
            } else {
                $countQuery->andWhere('a.tab = :tab')->setParameter('tab', $tab);
            }
        }

        $count = $countQuery->getQuery()->getSingleScalarResult();

        $allTabs = $this->getDoctrine()->getRepository('YesknMainBundle:Tab')
            ->findBy(['level' => 1]);

        $pageData['allPage'] = ceil($count/$pagesize);
        $pageData['currentPage'] = $page;

        $params = [
            'posts' => $posts,
            'tab' => $tab,
            'currentTab' => $tabObj,
            'tabs' => $allTabs,
            'sortBy' => $sortBy,
            'pageData' => $pageData
        ];

        if ($scope == 'home') {
            $tpl =  '@YesknMain/default/index.html.twig';
            $params['blog_list'] = $blogList;
        } else {
            $tpl = '@YesknMain/post/index.html.twig';
        }

        $response = $this->render($tpl, $params);

        if ($tabObj and $tabObj->getLevel() == 1) {
            $response->headers->setCookie(new Cookie('_tab', $tab));
        }

        if ($tab == 'all' or $tab == 'hot') {
            $response->headers->setCookie(new Cookie('_tab', $tab));
        }

        return $response;
    }

    /**
     * @Route("/test")
     */
    public function test()
    {
        $env = $this->getParameter('kernel.environment');

        if ($env !== 'dev') {
            return new Response('404 not found');
        }

        return $this->render('emails/verify-email.html.twig', [
            'code' => 1233
        ]);
    }
}
