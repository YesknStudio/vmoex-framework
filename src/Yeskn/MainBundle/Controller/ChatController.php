<?php
/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jake
 * Create: 2018-05-26 17:39:30
 */

namespace Yeskn\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Yeskn\MainBundle\Entity\Chat;
use Yeskn\MainBundle\Entity\User;

class ChatController extends Controller
{
    /**
     * @Route("/chat", name="bind_chat")
     */
    public function bindChatAction(Request $request)
    {
        $chats = $this->getDoctrine()->getRepository('YesknMainBundle:Chat')
            ->getLatestChat(100);

        if ($request->getSession()->has('chat.show_icon')) {
            $showIcon = false;
        } else {
            $showIcon = true;
            $request->getSession()->set('show_icon', 1);
        }

        $params = [
            'chats' => $chats,
        ];

        if ($showIcon) {
            $params['show_icon'] = 1;
        }

        $response = $this->render('@YesknMain/chat/chat.html.twig', $params);

        $lastChat = $this->getDoctrine()->getRepository('YesknMainBundle:Chat')->findOneBy([], ['id' => 'DESC']);

        if ($lastChat) {
            $lastChatId = $lastChat->getId();
        } else {
            $lastChatId = 0;
        }

        $response->headers->setCookie(new Cookie('_last_chat_id', $lastChatId));

        return $response;
    }

    /**
     * @Route("/bind-chat/send", methods={"POST"}, name="send_chat")
     *
     * @param $request
     * @return JsonResponse
     */
    public function sendChat(Request $request)
    {
        $content = $request->get('content');

        $chat = new Chat();

        $content = strip_tags($content);

        if (empty(strip_tags($content)) or mb_strlen($content) >= 200) {
            return new JsonResponse(['ret' => 0, 'msg' => '内个啥...长度好像不合适哦！']);
        }

        /**
         * @var User $user
         */
        $user = $this->getUser();

        if ($user->getGold() <= 0) {
            return new JsonResponse(['ret' => 0, 'msg' => 'no gold']);
        }

        $chat->setUser($user);
        $chat->setCreatedAt(new \DateTime());
        $chat->setContent($content);

        $em = $this->getDoctrine()->getManager();

        $cost = 1;

        $user->setGold($user->getGold()-$cost);

        $em->persist($chat);
        $em->flush();

        $this->get('socket.push')->pushAll('new_chat', [
            'username' => $user->getNickname(),
            'content' => $chat->getContent()
        ]);

        return new JsonResponse(['ret' => 1]);
    }
}
