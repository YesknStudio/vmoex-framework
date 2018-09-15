<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-15 09:38:58
 */

namespace Yeskn\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\MainBundle\Entity\Tab;

/**
 * Class TabController
 * @package Yeskn\AdminBundle\Controller
 *
 * @Route("/tab")
 */
class TabController extends Controller
{
    /**
     * @Route("/", name="admin_tab_index")
     */
    public function indexAction()
    {
        /** @var Tab[] $list */
        $list = $this->getDoctrine()->getRepository('YesknMainBundle:Tab')->findAll();

        return $this->render('@YesknAdmin/tab/index.html.twig', [
            'list' => $list
        ]);
    }
}