<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-15 10:12:29
 */

namespace Yeskn\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ManageController
 * @package Yeskn\AdminBundle\Controller
 *
 * @Route("/manage")
 */
class ManageController extends Controller
{
    /**
     * @Route("/basic", name="admin_manage_basic")
     */
    public function basicAction()
    {
        return $this->render('@YesknAdmin/manage/basic.html.twig');
    }
}