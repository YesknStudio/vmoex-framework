<?php
/**
 * This file is part of project JetBlog.
 *
 * Author: Jake
 * Create: 2018-05-27 04:39:20
 */

namespace Yeskn\BlogBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Yeskn\BlogBundle\Entity\Message;

class MessageController extends Controller
{
    /**
     * @Route("/send-message", name="send_message", methods={"POST"})
     */
    public function sendMessage(Request $request)
    {
        $content = $request->get('content');
        $to = $request->get('to');

        $em = $this->getDoctrine()->getManager();

        $receiver = $em->getRepository('YesknBlogBundle:User')->findOneBy(['username' => $to]);

        $message = new Message();

        $message->setReceiver($receiver);
        $message->setContent($content);
        $message->setSender($this->getUser());
        $message->setIsRead(false);
        $message->setCreatedAt(new \DateTime());

        $em->persist($message);
        $em->flush();

        return new JsonResponse(['ret' => 1]);
    }

    /**
     * @Route("/my-messages", name="my_messages", methods={"GET"})
     */
    public function myMessagesAction()
    {
        $messageRepository = $this->getDoctrine()->getRepository('YesknBlogBundle:Message');

        $rMessages = $messageRepository->findBy(['receiver' => $this->getUser()]);
        $sMessages = $messageRepository->findBy(['sender' => $this->getUser()]);

        return $this->render('@YesknBlog/messages.html.twig', [
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
        $messages = $this->getDoctrine()->getRepository('YesknBlogBundle:Message')
            ->createQueryBuilder('p')
            ->where('p.receiver = :user')->setParameter('user', $this->getUser())
            ->andWhere('p.isRead = false')
            ->getQuery()
            ->getResult(5);

        foreach ($messages as $message) {
            $message->setIsRead(true);
        }

        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(['ret' => 1]);
    }
}