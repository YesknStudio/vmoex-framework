<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-17 17:13:06
 */

namespace Yeskn\MainBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\Support\Http\ApiOk;

class StatusController extends Controller
{
    /**
     * @Route("/status")
     */
    public function indexAction()
    {
        $one = $this->getDoctrine()->getRepository('YesknMainBundle:Translation')
            ->findOneBy(['messageId' => 'site_name']);

        return new ApiOk([
            'site_name' => $one->getChinese()
        ]);
    }

    /**
     * @Route("/am-i-logged-in")
     * @Security("has_role('ROLE_USER')")
     *
     */
    public function amILoggedInAction()
    {
        return new ApiOk();
    }
}