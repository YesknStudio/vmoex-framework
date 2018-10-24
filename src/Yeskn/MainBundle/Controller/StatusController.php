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
use Symfony\Component\HttpFoundation\Cookie;
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
     * @Route("/status/close-alert", name="status_close_alert")
     */
    public function closeAnnounceAlert()
    {
        $response = new ApiOk();

        $announce = $this->getDoctrine()->getRepository('YesknMainBundle:Announce')
            ->findOneBy(['show' => 1], ['id' => 'DESC']);

        $response->headers->setCookie(new Cookie('_announce', $announce->getId()));

        return $response;
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
