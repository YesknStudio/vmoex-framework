<?php

namespace Yeskn\MainBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@YesknWpcraft/Default/index.html.twig');
    }

    public function loginAction()
    {
        return $this->render('@YesknWpcraft/login.html.twig');
    }

    /**
     * @Route("/basic", name="basic")
     */
    public function basicAction()
    {
        return new Response('hello, world');
    }


    /**
     * @Route("/login_check" , name="login_check")
     * @throws \Exception
     */
    public function loginCheckAction()
    {
        throw new \Exception('This should never be reached!');
    }

    /**
     * @Route("/logout" ,name="yeskn_user_logout")
     * @throws
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function logoutAction()
    {
        throw new \Exception('This should never be reached!');
    }
}
