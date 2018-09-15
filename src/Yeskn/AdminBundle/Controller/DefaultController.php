<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jaggle
 * Create: 2018-09-12 14:30:12
 */

namespace Yeskn\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="admin_index")
     */
    public function indexAction()
    {
        $postCount = $this->getDoctrine()->getRepository('YesknMainBundle:Post')->countPost();
        $userCount = $this->getDoctrine()->getRepository('YesknMainBundle:User')->countUser();
        $commentCount = $this->getDoctrine()->getRepository('YesknMainBundle:Comment')->countComment();

        $todayLoginUserCount = $this->getDoctrine()->getRepository('YesknMainBundle:User')
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
