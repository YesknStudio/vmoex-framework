<?php
/**
 * This file is part of project Vmoex.
 *
 * Author: Jake
 * Create: 2018-06-10 12:05:03
 */

namespace Yeskn\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class TabsController
 * @package Yeskn\BlogBundle\Controller
 *
 * @Route("/tabs")
 */
class TabsController extends Controller
{
    /**
     * @Route("", name="tabs_index")
     * @throws
     */
    public function indexAction()
    {
        $count = $this->getDoctrine()->getRepository('YesknBlogBundle:Tab')
            ->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->getQuery()
            ->getSingleScalarResult();

        $tabs = $this->getDoctrine()->getRepository('YesknBlogBundle:Tab')->findAll();


        return $this->render('@YesknBlog/tabs.html.twig', [
            'tabs' => $tabs,
            'tab_count' => $count
        ]);
    }
}