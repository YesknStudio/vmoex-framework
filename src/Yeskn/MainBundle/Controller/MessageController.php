<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-14 15:20:04
 */

namespace Yeskn\MainBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Yeskn\MainBundle\Entity\Message;
use Yeskn\MainBundle\Form\UserMessageType;
use Yeskn\Support\Http\ApiOk;

/**
 * Class MessageController
 * @package Yeskn\MainBundle\Controller
 *
 * @Security("has_role('ROLE_USER')")
 *
 */
class MessageController extends Controller
{
    /**
     * @Route("/send-message", name="send_message", methods={"POST"})
     * 
     * @param $request
     * @return JsonResponse
     */
    public function sendMessage(Request $request)
    {
        $message = new Message();

        $form = $this->createForm(UserMessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $message->setSender($this->getUser());
            $message->setCreatedAt(new \DateTime());
            $message->setContent(strip_tags($message->getContent()));

            $em->persist($message);
            $em->flush();
        }

        $this->get('socket.push')->pushNewMessage($message);

        return new ApiOk();
    }

    /**
     * @Route("/set-message-red", name="set_message_red", methods={"POST"})
     */
    public function setReadAction()
    {
        /**
         * @var Message[] $messages
         */
        $messages = $this->getDoctrine()->getRepository('YesknMainBundle:Message')
            ->createQueryBuilder('p')
            ->where('p.receiver = :user')->setParameter('user', $this->getUser())
            ->andWhere('p.isRead = false')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult(5);

        foreach ($messages as $message) {
            $message->setIsRead(true);
        }

        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(['ret' => 1]);
    }
}