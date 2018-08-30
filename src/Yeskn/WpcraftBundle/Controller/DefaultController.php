<?php

namespace Yeskn\WpcraftBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('YesknWpcraftBundle:Default:index.html.twig');
    }
}
