<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-15 13:31:54
 */

namespace Yeskn\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\MainBundle\Entity\Message;
use Yeskn\MainBundle\Form\UserMessageType;

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

        $message = new Message();

        $message->setReceiver($user);
        $message->setSender($this->getUser());

        return $this->render('@YesknMain/member/home.html.twig', [
            'user' => $user,
            'online' => $online,
            'userActive' => $userActive,
            'form' => $this->createForm(UserMessageType::class, $message)->createView()
        ]);
    }
}
