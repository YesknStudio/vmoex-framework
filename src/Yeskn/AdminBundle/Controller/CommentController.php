<?php

/*
 * This file is part of project yeskn/vmoex.
 *
 * (c) Jaggle <jaggle@yeskn.com>
 *
 * created at 2016-06-23 12:35
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
        $pageData = $this->getDoctrine()->getRepository('YesknWebBundle:Comment')
            ->getPageData($request->get('page'));
        return $this->render('@YesknAdmin/Comment/list.html.twig', [
            'paginator' => $this->getPaginator($pageData->count),
            'comments' => $pageData->data
        ]);
    }
}