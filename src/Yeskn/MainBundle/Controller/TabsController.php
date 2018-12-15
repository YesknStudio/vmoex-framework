<?php

/*
 * This file is part of project yeskn/vmoex.
 *
 * (c) Jaggle <jaggle@yeskn.com>
 *
 * created at 2018-06-10 12:05:03
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yeskn\MainBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class TabsController
 * @package Yeskn\MainBundle\Controller
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
        $count = $this->getDoctrine()->getRepository('YesknMainBundle:Tab')
            ->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->getQuery()
            ->getSingleScalarResult();

        $tabs = $this->getDoctrine()->getRepository('YesknMainBundle:Tab')->findAll();


        return $this->render('@YesknMain/tabs.html.twig', [
            'tabs' => $tabs,
            'tab_count' => $count
        ]);
    }
}
