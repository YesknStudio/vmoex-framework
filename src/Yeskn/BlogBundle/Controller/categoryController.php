<?php

namespace Yeskn\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class categoryController
 * @Route("/category")
 * @package Yeskn\BlogBundle\Controller
 */
class categoryController extends Controller
{
    /**
     * @Route("/" , name="category_homepage")
     */
    public function indexAction()
    {
        return $this->render('YesknBlogBundle:category:index.html.twig', array(
            // ...
        ));
    }

}
