<?php
/**
 * This file is part of project vmoex.
 * User: Jake
 * Date: 2016/6/23
 * Time: 12:35
 */

namespace Yeskn\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Yeskn\CommonBundle\Controller\BaseController;

/**
 * Class CommentController
 * @package Yeskn\AdminBundle\Controller
 *
 * @Route("/admin/comment")
 */
class CommentController extends BaseController
{
    /**
     * @Route("", name="admin_comment_index")
     */
    public function indexAction(Request $request)
    {
        $pageData = $this->getDoctrine()->getRepository('YesknBlogBundle:Comment')
            ->getPageData($request->get('page'));
        return $this->render('@YesknAdmin/Comment/list.html.twig', [
            'paginator' => $this->getPaginator($pageData->count),
            'comments' => $pageData->data
        ]);
    }
}