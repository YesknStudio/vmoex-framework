<?php

namespace Yeskn\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class tagController
 * @package Yeskn\WebBundle\Controller
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
        $tag = $this->getDoctrine()->getRepository('YesknWebBundle:Tag')->find($id);
        $posts = $this->getDoctrine()->getRepository('YesknWebBundle:Post')->testQuery();

    }
}
