<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-14 15:24:09
 */

namespace Yeskn\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package Yeskn\MainBundle\Controller
 *
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/home", name="user_home")
     */
    public function homeAction()
    {

    }

    /**
     * @Route("/setting", name="user_setting")
     */
    public function settingAction()
    {

    }
}