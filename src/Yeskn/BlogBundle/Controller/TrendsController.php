<?php
/**
 * This file is part of project Vmoex.
 *
 * Author: Jake
 * Create: 2018-06-10 12:00:07
 */

namespace Yeskn\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class TrendsController
 * @package Yeskn\BlogBundle\Controller
 *
 * @Route("/trends")
 */
class TrendsController extends Controller
{
    /**
     * @Route("", name="trends_index")
     */
    public function indexAction()
    {
        return $this->render('@YesknBlog/trends.html.twig');
    }
}