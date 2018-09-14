<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-14 18:01:19
 */

namespace Yeskn\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class InfoController extends Controller
{
    /**
     * @Route("/info", name="info", methods={"GET"})
     */
    public function infoAction()
    {
        $user = $this->getUser();

        $messages =  $this->getDoctrine()->getRepository('YesknMainBundle:Message')
            ->getUnReadMessages($user);

        $messageRet = [];

        foreach ($messages as &$message) {
            $messageRet[] = [
                'createdAt' => $message->getCreatedAt()->format('Y-m-d H:i:s'),
                'content' => $message->getContent(),
                'sender' => $message->getSender()->getNickname(),
                'sender_username' => $message->getSender()->getUsername()
            ];
        }

        return new JsonResponse([
            'messages' => $messageRet ?: null
        ]);
    }
}