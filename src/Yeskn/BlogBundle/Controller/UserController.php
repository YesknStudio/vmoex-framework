<?php
/**
 * This file is part of project JetBlog.
 *
 * Author: Jake
 * Create: 2018-05-26 01:46:15
 */

namespace Yeskn\BlogBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    /**
     * @Route("/user/{username}", name="user_home")
     */
    public function userHomeAction($username)
    {
        $user = $this->getDoctrine()->getRepository('YesknBlogBundle:User')
            ->findOneBy(['username' => $username]);
        if (!$user) {
            return $this->render('@YesknBlog/error.html.twig', [
                'message' => '用户不存在'
            ]);
        }

        return $this->render('@YesknBlog/user/user_home.html.twig', [
            'username' => $user->getNickname()
        ]);
    }
}