<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-14 23:01:27
 */

namespace Yeskn\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\MainBundle\Entity\User;
use Yeskn\MainBundle\Form\UserType;

/**
 * Class UserController
 * @package Yeskn\AdminBundle\Controller
 *
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="admin_user_index")
     */
    public function indexAction()
    {
        $list = $this->getDoctrine()->getRepository('YesknMainBundle:User')->findAll();

        return $this->render('@YesknAdmin/user/index.html.twig', [
            'list' => $list,
            'form' => $this->createForm(UserType::class, new User())->createView()
        ]);
    }
}