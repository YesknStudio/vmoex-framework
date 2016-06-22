<?php

namespace Yeskn\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('YesknUserBundle:Default:index.html.twig');
    }
}
