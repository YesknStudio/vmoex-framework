<?php

namespace Yeskn\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Yeskn\BlogBundle\Entity\Comment;
use Yeskn\BlogBundle\Entity\Post;
use Yeskn\BlogBundle\Entity\User;

class DefaultController extends Controller
{
    /**
     * @Route("/", defaults={"page": 1},name="yeskn_blog_homepage")
     * @Route("/page/{page}", requirements={"page": "[1-9]\d*"}, name="blog_index_paginated")
     * @Method("GET")
     * @param $page integer
     * @Cache(smaxage="10")
     * @throws
     * @return Response
     */
    public function indexAction($page)
    {
        $posts = $this->getDoctrine()->getRepository('YesknBlogBundle:Post')->findBy(
            array(),
            array('id' => 'DESC'),
            10,
            10*($page-1)
        );

        $count = $this->getDoctrine()->getRepository('YesknBlogBundle:Post')
            ->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.isDeleted = false')
            ->getQuery()
            ->getSingleScalarResult();

        $pageData['allPage'] = ceil($count/10);
        $pageData['currentPage'] = $page;

        return $this->render('YesknBlogBundle:Default:index.html.twig', array(
            'posts' => $posts,
            'pageData' => $pageData
        ));
    }

    /**
     * @inheritdoc
     * @Route("/post/{id}", name="yeskn_blog_show")
     */
    public function postShowAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $this->getDoctrine()->getRepository('YesknBlogBundle:Post')->find($id);
        if (empty($post)) {
            return $this->render('@YesknBlog/error.html.twig', [
                'message' => '文章不存在'
            ]);
        }
        $post->setViews(intval($post->getViews())+1);
        $em->flush();
        return $this->render('YesknBlogBundle:Default:show.html.twig', array(
            'post' => $post
        ));
    }

    /**
     * @Route("/post/{postId}/comment/add", name="add_comment_to_post")
     *
     * @param $request
     * @param $postId
     * @return JsonResponse
     */
    public function addCommentToPostAction(Request $request, $postId)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $this->getDoctrine()->getRepository('YesknBlogBundle:Post')->find($postId);
        if (empty($post)) {
            return new JsonResponse(['err' => '文章不存在']);
        }

        $comment = new Comment();

        $comment->setContent($request->get('content'));
        $comment->setCreatedAt(new \DateTime());
        $comment->setPost($post);
        $comment->setUser($this->getUser());
        $comment->setReplyTo(0);

        $em->persist($comment);

        $em->flush();

        return new JsonResponse(['ret' => 1]);
    }

    /**
     *
     * @Route("/search")
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function searchAction(Request $request)
    {
        $s = $request->get('s');
        $c = $request->get('c');

        $em = $this->getDoctrine()->getManager();

        $postResults = $em->getRepository('YesknBlogBundle:Post')->queryPosts($s, $c);

        $userResults = $em->getRepository('YesknBlogBundle:User')->queryUser($s, $c);

        return $this->render('@YesknBlog/search.html.twig', [
            'word' => $s,
            'posts' => $postResults,
            'users' => $userResults
        ]);
    }
}
