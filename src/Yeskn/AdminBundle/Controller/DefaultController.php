<?php

namespace Yeskn\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DefaultController
 * @package Yeskn\AdminBundle\Controller
 */
class DefaultController extends Controller
{

    /**
     * @Route("/admin")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $postCount = $this->getDoctrine()->getRepository('YesknWebBundle:Post')->countPost();
        $userCount = $this->getDoctrine()->getRepository('YesknWebBundle:User')->countUser();
        $commentCount = $this->getDoctrine()->getRepository('YesknWebBundle:Comment')->countComment();

        $todayLoginUserCount = $this->getDoctrine()->getRepository('YesknWebBundle:User')
            ->getTodayLoggedUserCount();

        return $this->render('YesknAdminBundle:Default:index.html.twig', [
            'count' => [
                'post' => $postCount,
                'user' => $userCount,
                'todayLoginUserCount' => $todayLoginUserCount,
                'comment' => $commentCount
            ]
        ]);
    }
}
