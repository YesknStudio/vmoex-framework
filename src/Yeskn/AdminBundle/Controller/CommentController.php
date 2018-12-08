<?php

/**
 * This file is part of project project yeskn-studio/vmoex-framework.
 *
 * Author: Jaggle
 * Create: 2018-09-15 09:01:05
 */

namespace Yeskn\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\MainBundle\Entity\Comment;
use Yeskn\MainBundle\Form\CommentType;

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
            'list' => $list,
            'form' => $this->createForm(CommentType::class, new Comment())->createView()
        ));
    }
}
