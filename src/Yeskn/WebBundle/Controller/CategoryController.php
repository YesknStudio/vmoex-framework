<?php

/*
 * This file is part of project yeskn/vmoex.
 *
 * (c) Jaggle <jaggle@yeskn.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
