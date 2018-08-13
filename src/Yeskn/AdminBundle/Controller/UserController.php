<?php

/*
 * This file is part of project yeskn/vmoex.
 *
 * (c) Jaggle <jaggle@yeskn.com>
 *
 * created at 2016/6/22 21:36
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yeskn\AdminBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class UserController
 * @Route("/admin/user")
 * @package Yeskn\AdminBundle\Controller
 */
class UserController extends Controller
{
    /**
     * @Route("/index")
     */
    public function indexAction()
    {
        $users = $this->getDoctrine()->getRepository('YesknWebBundle:User')->findAll();
        return $this->render('YesknAdminBundle:User:index.html.twig',array(
            'users' => $users
        ));
    }

    /**
     * @Route("/add")
     */
    public function addAction()
    {
        return $this->render('@YesknAdmin/User/add.html.twig');
    }
}