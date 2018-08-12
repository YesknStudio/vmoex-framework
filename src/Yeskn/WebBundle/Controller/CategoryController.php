<?php

namespace Yeskn\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class categoryController
 * @Route("/category")
 * @package Yeskn\WebBundle\Controller
 */
class CategoryController extends Controller
{
    /**
     * @Route("/" , name="category_homepage")
     */
    public function indexAction()
    {
        return $this->render('YesknWebBundle:category:index.html.twig', array(
            // ...
        ));
    }

}
