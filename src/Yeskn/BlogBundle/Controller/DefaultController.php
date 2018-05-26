<?php

namespace Yeskn\BlogBundle\Controller;

use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @Route("/test")
     */
    public function testAction()
    {
        $post = new Post();
        $post->setTitle('Hello,welcome to symfony!');
        $post->setAuthor(1);
        $post->setExcerpt('follow me and you will enjoy it !');
        $post->setContent($post->getExcerpt());
        $post->setCreatedAt(new \DateTime());
        $post->setIsDeleted(false);
        $post->setStatus('published');

        $em = $this->getDoctrine()->getManager();

        $em->persist($post);
        $em->flush();

        return new Response('saved new post with id ' . $post->getId());
    }

    /**
     * @Route("/test/show/{postId}")
     * @param $postId integer
     * @throws \Exception
     */
    public function showTestAction($postId)
    {
        $post = $this->getDoctrine()->getRepository('YesknBlogBundle:Post')
            ->find($postId);
        if(!$post){
            throw $this->createNotFoundException(
                'No product found for id' . $postId
            );
        }
    }



    /**
     * @Route("/test/reg")
     */
    public function regTestAction()
    {
        $user = new User();
        $user->setUsername('Jake');
        $plainPassword = '123456';
        $user->setPassword(
            $this->container->get('security.password_encoder')
                ->encodePassword($user,$plainPassword)
        );
        $user->setNickname('杰斯');
        $user->setEmail('singviy@gmail.com');
        $user->setRegisterAt(new \DateTime());
        $user->setLoginAt($user->getRegisterAt());
        $user->setType('admin');
        $user->setApiKey('this is a api key');

        $em =  $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return new Response('add new user with userId ' . $user->getId());
    }

    /**
     * @Route("/create/test")
     */
    public function createTestAction()
    {
        $user = $this->getUser();
        $post = new Post();
        $post->setTitle('Centos下Yum安装PHP5.5,5.6,7.0');
        $post->setAuthor(1);
        $post->setExcerpt('');
        $post->setContent($post->getExcerpt());
        $post->setCreatedAt(new \DateTime());
        $post->setIsDeleted(false);
        $post->setStatus('published');
        $post->setAuthor($user);

        $em = $this->getDoctrine()->getManager();

        $em->persist($post);
        $em->flush();

        return new Response(
            ' and new category with id: '.$post->getId()
        );




    }
}
