<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-15 13:31:54
 */

namespace Yeskn\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MemberController
 * @package Yeskn\MainBundle\Controller
 *
 * @Route("/member")
 */
class MemberController extends Controller
{
    /**
     * @Route("/{username}", name="member_home")
     *
     * @param $username
     * @return  Response
     */
    public function homeAction($username)
    {
        $user = $this->getDoctrine()->getRepository('YesknMainBundle:User')
            ->findOneBy(['username' => $username]);
        if (!$user) {
            return $this->render('@YesknMain/error.html.twig', [
                'message' => '用户不存在'
            ]);
        }

        $userActive = $this->getDoctrine()->getRepository('YesknMainBundle:Active')
            ->findOneBy(['user' => $user], ['id' => 'DESC']);

        $online = false;

        if ($userActive) {

            /** @var \DateTime $updatedAt */
            $updatedAt = $userActive->getUpdatedAt();

            if ($userActive and $updatedAt->getTimestamp() >= time() - 15*60) {
                $online = true;
            }
        }

        return $this->render('@YesknMain/user/user-home.html.twig', [
            'user' => $user,
            'online' => $online,
            'userActive' => $userActive
        ]);
    }
}