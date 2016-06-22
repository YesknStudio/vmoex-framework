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
        return $this->render('YesknAdminBundle:Default:index.html.twig');
    }
}
