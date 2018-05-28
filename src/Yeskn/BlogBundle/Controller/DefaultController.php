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
        $pagesize = 25;
        $posts = $this->getDoctrine()->getRepository('YesknBlogBundle:Post')
            ->findBy([], ['id' => 'DESC'], $pagesize, $pagesize*($page-1));

        $count = $this->getDoctrine()->getRepository('YesknBlogBundle:Post')
            ->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.isDeleted = false')
            ->getQuery()
            ->getSingleScalarResult();

        $pageData['allPage'] = ceil($count/$pagesize);
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

        $content = $request->get('content');

        $content = strip_tags($content, '<p><br><a><strong><span><i><u><strike><b><font>');

        $comment = new Comment();

        $comment->setContent($content);
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
        $s = strip_tags($request->get('s'));
        $count = $request->get('c');

        $em = $this->getDoctrine()->getManager();

        $postResults = $em->getRepository('YesknBlogBundle:Post')->queryPosts($s, $count);

        $userResults = $em->getRepository('YesknBlogBundle:User')->queryUser($s, $count);

        return $this->render('@YesknBlog/search.html.twig', [
            'word' => $s,
            'posts' => $postResults,
            'users' => $userResults
        ]);
    }

    /**
     * @Route("/about", name="about")
     *
     * @return Response
     */
    public function aboutAction(Request $request)
    {
        return $this->render('@YesknBlog/about.html.twig');
    }

    /**
     * @Route("/follow", name="follow_user", methods={"POST"})
     * @return Response
     */
    public function followAction(Request $request)
    {
        $username = $request->get('username');

        /**
         * @var User $me
         */
        $me = $this->getUser();

        if (empty($me)) {
            return new JsonResponse([
                'ret' => 0,
                'msg' => '请先登录再进行操作哦'
            ]);
        }

        $ta = $this->getDoctrine()->getRepository('YesknBlogBundle:User')
            ->findOneBy(['username' => $username]);
        $em = $this->getDoctrine()->getManager();

        if ($ta->followers()->contains($me)) {
            $me->unfollow($ta);
        } else {
            $me->follow($ta);
        }

        $em->flush();

        return new JsonResponse(['ret' => 1]);
    }

    /**
     * @Route("/info", name="info", methods={"GET"})
     */
    public function infoAction()
    {
        $user = $this->getUser();

        $messages =  $this->getDoctrine()->getRepository('YesknBlogBundle:Message')
            ->getUnReadMessages($user);

        $messageRet = [];

        foreach ($messages as &$message) {
            $messageRet[] = [
                'createdAt' => $message->getCreatedAt()->format('Y-m-d H:i:s'),
                'content' => $message->getContent(),
                'sender' => $message->getSender()->getNickname(),
                'sender_username' => $message->getSender()->getUsername()
            ];
        }

        return new JsonResponse([
            'messages' => $messageRet ?: null
        ]);
    }

    /**
     * @Route("/test")
     */
    public function testAction()
    {
        $identicon = new \Identicon\Identicon();

        $i = $identicon->getImageDataUri('singviy@gg.com');


        $res = new Response();

        $res->setContent($i);
        //$res->headers->set('Content-Type', 'image/png');
        return $res;
    }
}
