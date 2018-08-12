<?php
/**
 * This file is part of project Vmoex.
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
use Yeskn\BlogBundle\Entity\User;
use Yeskn\BlogBundle\Utils\HtmlPurer;

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

        $content = strip_tags($content, '<p><br><a><strong><span><i><u><strike><b><font>');

        $htmlPurer  = new HtmlPurer();
        $content = $htmlPurer->pure($content)->getResult();

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

        if ($htmlPurer->hasColor()) {
            $cost = 5;
        }

        $user->setGold($user->getGold()-$cost);

        $em->persist($chat);
        $em->flush();

        $this->get('socket.push')->pushAll('new_chat');

        return new JsonResponse(['ret' => 1]);
    }
}