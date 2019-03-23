<?php
/**
 * This file is part of project project yeskn-studio/vmoex-framework.
 *
 * Author: Jaggle
 * Create: 2018-05-26 17:39:30
 */

namespace Yeskn\MainBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;
use Yeskn\MainBundle\Entity\Chat;
use Yeskn\MainBundle\Entity\User;
use Yeskn\Support\Http\ApiFail;
use Yeskn\Support\Http\ApiOk;

class ChatController extends Controller
{
    /**
     * @Route("/chat", name="bind_chat")
     * @Method("GET")
     *
     * @param Request $request
     * @return Response
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

        $lastChat = $this->getDoctrine()
            ->getRepository('YesknMainBundle:Chat')->findOneBy([], ['id' => 'DESC']);

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
     * @Security("has_role('ROLE_USER')")
     *
     * @param $request
     * @param TranslatorInterface $trans
     * @return JsonResponse
     */
    public function sendChat(Request $request, TranslatorInterface $trans)
    {
        $content = $request->get('content');
        $content = strip_tags($content);

        if (empty(strip_tags($content)) or mb_strlen($content) >= 200) {
            return new ApiFail($trans->trans('length_not_support'));
        }

        /**
         * @var User $user
         */
        $user = $this->getUser();

        if ($user->getGold() <= 0) {
            return new ApiFail($trans->trans('no_enough_gold'));
        }

        $chat = new Chat();

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

        return new ApiOk();
    }
}
