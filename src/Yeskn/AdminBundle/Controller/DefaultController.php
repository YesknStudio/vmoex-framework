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
        $postCount = $this->getDoctrine()->getRepository('YesknBlogBundle:Post')->countPost();
        $userCount = $this->getDoctrine()->getRepository('YesknBlogBundle:User')->countUser();
        $commentCount = $this->getDoctrine()->getRepository('YesknBlogBundle:Comment')->countComment();

        $todayLoginUserCount = $this->getDoctrine()->getRepository('YesknBlogBundle:User')
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
