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

class ChatController extends Controller
{
    /**
     * @Route("/bind-chat", name="bind_chat")
     */
    public function bindChatAction()
    {
        return $this->render('@YesknBlog/chat.html.twig');
    }
}