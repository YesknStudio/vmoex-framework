<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jaggle
 * Create: 2018-09-22 11:36:13
 */

namespace Yeskn\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;

class CommonController extends Controller
{
    public function homeListAction(Request $request)
    {
        $tab = $request->get('tab');
        $page = $request->get('page', 1);
        $scope = $request->get('scope');
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

        if ($tab && !in_array($tab, ['hot', 'all'])) {
            $tabObj = $this->getDoctrine()->getRepository('YesknMainBundle:Tab')
                ->findOneBy(['alias' => $tab]);
            if (empty($tabObj)) {
                // wrong error message
                $this->addFlash('error', '嘤嘤嘤，板块不存在呢~');
                return $this->redirectToRoute('homepage');
            }
        }

        list($count, $posts) = $this->getDoctrine()->getRepository('YesknMainBundle:Post')
            ->getIndexList($tabObj, $sort, [$page, $pagesize]);

        $allTabs = $this->getDoctrine()->getRepository('YesknMainBundle:Tab')
            ->findBy(['level' => 1]);

        $pageData['allPage'] = ceil($count / $pagesize);
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
}
