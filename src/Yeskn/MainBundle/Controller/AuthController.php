<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-14 15:26:13
 */

namespace Yeskn\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends Controller
{
    /**
     * @Route("/login", name="login", methods={"GET"})
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        //get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        //last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@YesknMain/Auth/login.html.twig', array(
            // ...
            'last_username' => $lastUsername,
            'error'         => $error
        ));
    }

    /**
     * @Route("/login", name="do_login", methods={"POST"})
     */
    public function doLoginAction()
    {
        throw new \Exception('this should never be reached!');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        throw new \Exception('this should never be reached!');
    }

    /**
     * @Route("/register", name="register")
     */
    public function registerAction()
    {

    }
}