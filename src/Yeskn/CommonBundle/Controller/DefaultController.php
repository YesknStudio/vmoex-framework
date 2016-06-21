<?php

namespace Yeskn\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('YesknCommonBundle:Default:index.html.twig');
    }
}
