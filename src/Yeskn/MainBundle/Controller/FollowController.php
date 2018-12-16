<?php

/**
 * This file is part of project project yeskn-studio/vmoex-framework.
 *
 * Author: Jaggle
 * Create: 2018-09-14 17:03:54
 */

namespace Yeskn\MainBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\MainBundle\Entity\User;

/**
 * Class FollowController
 * @package Yeskn\MainBundle\Controller
 *
 * @Security("has_role('ROLE_USER')")
 */
class FollowController extends Controller
{

    /**
     * @Route("/follow", name="follow_user", methods={"POST"})
     *
     * @param $request
     * @return JsonResponse
     */
    public function followAction(Request $request)
    {
        $username = $request->get('username');

        /**
         * @var User $me
         */
        $me = $this->getUser();

        /** @var User $ta */
        $ta = $this->getDoctrine()->getRepository('YesknMainBundle:User')
            ->findOneBy(['username' => $username]);
        $em = $this->getDoctrine()->getManager();

        if ($ta->followers()->contains($me)) {
            $me->unfollow($ta);
            $action = 0;
        } else {
            $me->follow($ta);
            $action = 1;
        }

        $em->flush();

        if ($action) {
            $this->get('socket.push')->pushNewFollowerNotification($me, $ta);
        }

        return new JsonResponse(['ret' => 1]);
    }
}
