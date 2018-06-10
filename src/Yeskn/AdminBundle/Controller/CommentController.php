<?php
/**
 * Created by PhpStorm.
 * User: Jake
 * Date: 2016/6/23
 * Time: 12:35
 */

namespace Yeskn\AdminBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class CommentController
 * @package Yeskn\AdminBundle\Controller
 *
 * @Route("/admin/comment")
 */
class CommentController extends Controller
{
    /**
     * @Route("", name="admin_comment_index")
     */
    public function indexAction()
    {
        $comments = $this->getDoctrine()->getRepository('YesknBlogBundle:Comment')->findAll();
        return $this->render('@YesknAdmin/Comment/list.html.twig', [
            'comments' => $comments
        ]);
    }
}