<?php
/**
 * This file is part of project JetBlog.
 *
 * Author: Jake
 * Create: 2018-05-26 17:39:30
 */

namespace Yeskn\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Yeskn\BlogBundle\Entity\Chat;

class ChatController extends Controller
{
    /**
     * @Route("/blind-chat", name="bind_chat")
     */
    public function bindChatAction()
    {
        $chats = $this->getDoctrine()->getRepository('YesknBlogBundle:Chat')
            ->getLatestChat(100);
        return $this->render('@YesknBlog/chat.html.twig', [
            'chats' => $chats
        ]);
    }

    /**
     * @Route("/bind-chat/send", methods={"POST"}, name="send_chat")
     */
    public function sendChat(Request $request)
    {
        $content = $request->get('content');

        $chat = new Chat();

        $chat->setUser($this->getUser());
        $chat->setCreatedAt(new \DateTime());
        $chat->setContent($content);

        $em = $this->getDoctrine()->getManager();

        $em->persist($chat);
        $em->flush();

        return new JsonResponse(['ret' => 1]);
    }
}