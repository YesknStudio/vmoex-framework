<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-14 15:20:04
 */

namespace Yeskn\MainBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Yeskn\MainBundle\Entity\Message;
use Yeskn\MainBundle\Entity\User;

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
        /**
         * @var User $user
         */
        $user = $this->getUser();

        if (empty($user)) {
            return new JsonResponse(['ret' => 0, 'msg' => 'nologin']);
        }

        $content = $request->get('content');
        $content = strip_tags($content);
        $to = $request->get('to');

        $em = $this->getDoctrine()->getManager();

        $receiver = $em->getRepository('YesknMainBundle:User')->findOneBy(['username' => $to]);

        $message = new Message();

        $message->setReceiver($receiver);
        $message->setContent($content);
        $message->setSender($user);
        $message->setIsRead(false);
        $message->setCreatedAt(new \DateTime());

        $em->persist($message);
        $em->flush();

        $this->get('socket.push')->pushNewMessage($message);

        return new JsonResponse(['ret' => 1]);
    }

    /**
     * @Route("/my-messages", name="my_messages", methods={"GET"})
     */
    public function myMessagesAction()
    {
        $messageRepository = $this->getDoctrine()->getRepository('YesknMainBundle:Message');

        /** @var Message[] $rMessages */
        $rMessages = $messageRepository->findBy(['receiver' => $this->getUser()], ['createdAt' => 'DESC']);
        /** @var Message[] $sMessages */
        $sMessages = $messageRepository->findBy(['sender' => $this->getUser()],['createdAt' => 'DESC']);

        return $this->render('@YesknMain/user/messages.html.twig', [
            'rMessages' => $rMessages,
            'sMessages' => $sMessages
        ]);

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