<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-15 13:31:54
 */

namespace Yeskn\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;
use Yeskn\MainBundle\Entity\Message;
use Yeskn\MainBundle\Form\UserMessageType;
use Yeskn\Support\AbstractController;

/**
 * Class MemberController
 * @package Yeskn\MainBundle\Controller
 *
 * @Route("/member")
 */
class MemberController extends AbstractController
{
    /**
     * @Route("/{username}", name="member_home")
     *
     * @param $username
     * @return Response
     */
    public function homeAction($username, TranslatorInterface $trans)
    {
        $user = $this->getDoctrine()->getRepository('YesknMainBundle:User')
            ->findOneBy(['username' => $username]);
        if (!$user) {
            return $this->errorResponse($trans->trans('user_not_exist'));
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
