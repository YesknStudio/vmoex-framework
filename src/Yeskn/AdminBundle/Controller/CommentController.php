<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jaggle
 * Create: 2018-09-15 09:01:05
 */

namespace Yeskn\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\MainBundle\Entity\Comment;

/**
 * Class CommentController
 * @package Yeskn\AdminBundle\Controller
 *
 * @Route("/comment")
 */
class CommentController extends Controller
{
    /**
     * @Route("/", name="admin_comment_index")
     */
    public function indexAction()
    {
        /**
         * @var Comment[] $posts
         */
        $list = $this->getDoctrine()->getRepository('YesknMainBundle:Comment')->findBy([], ['createdAt' => 'DESC']);

        return $this->render('@YesknAdmin/comment/index.html.twig', array(
            'list' => $list
        ));
    }
}