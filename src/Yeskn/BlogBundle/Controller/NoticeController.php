<?php
/**
 * This file is part of project JetBlog.
 *
 * Author: Jake
 * Create: 2018-05-27 12:59:41
 */

namespace Yeskn\BlogBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NoticeController extends Controller
{
    /**
     * @Route("/my-notices", name="my_notices")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function myNoticesAction()
    {
        return $this->render('@YesknBlog/notices.html.twig');
    }
}