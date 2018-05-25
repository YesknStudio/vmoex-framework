<?php

namespace Yeskn\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class tagController
 * @package Yeskn\BlogBundle\Controller
 */
class TagController extends Controller
{
    /**
     * @Route("/tag",name="tag_homepage")
     * @Route("/tags")
     */
    public function indexAction()
    {
        return new Response();
    }

    /**
     * @Route("/tag/{id}")
     * @inheritdoc
     */
    public function tagPage($id)
    {
        $tag = $this->getDoctrine()->getRepository('YesknBlogBundle:Tag')->find($id);
        $posts = $this->getDoctrine()->getRepository('YesknBlogBundle:Post')->testQuery();

    }
}
