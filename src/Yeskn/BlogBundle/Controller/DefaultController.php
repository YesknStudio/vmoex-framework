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
use Yeskn\BlogBundle\Entity\Notice;
use Yeskn\BlogBundle\Entity\Post;
use Yeskn\BlogBundle\Entity\User;
use Yeskn\BlogBundle\Utils\HtmlPurer;

class DefaultController extends Controller
{
    /**
     * @Route("/", defaults={"page": 1},name="yeskn_blog_homepage")
     * @Route("/tab/{tab}", defaults={"page":1}, name="home_tab")
     * @Route("/tab/{tab}/{page}", defaults={"page":1}, name="home_tab_paged")
     * @Route("/page/{page}", requirements={"page": "[1-9]\d*"}, name="blog_index_paginated")
     * @Method("GET")
     * @param $page integer
     * @Cache(smaxage="10")
     * @param Request $request
     * @throws
     * @return Response
     */
    public function indexAction(Request $request, $page)
    {
        $tab = $request->attributes->get('tab', 'all');
        $pagesize = 25;

        $sort = ['updatedAt' => 'DESC'];

        if ($tab == 'hot') {
            $sort = ['views' => 'DESC'];
        }

        $condition = [];

        if ($tab and $tab != 'all' and $tab != 'hot') {
            $tabObj = $this->getDoctrine()->getRepository('YesknBlogBundle:Tab')
                ->findOneBy(['alias' => $tab]);
            if (empty($tabObj)) {
                return new JsonResponse('tab not exists');
            }
            $condition = ['tab' => $tabObj];
        }

        $posts = $this->getDoctrine()->getRepository('YesknBlogBundle:Post')
            ->findBy($condition, $sort, $pagesize, $pagesize*($page-1));

        $countQuery = $this->getDoctrine()->getRepository('YesknBlogBundle:Post')
            ->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.isDeleted = false');

        if (!empty($tabObj)) {
            $countQuery->andWhere('a.tab = :tab')->setParameter('tab', $tabObj);

        }

        $count = $countQuery->getQuery()->getSingleScalarResult();

        $allTabs = $this->getDoctrine()->getRepository('YesknBlogBundle:Tab')
            ->findAll();

        $pageData['allPage'] = ceil($count/$pagesize);
        $pageData['currentPage'] = $page;

        return $this->render('YesknBlogBundle:Default:index.html.twig', array(
            'posts' => $posts,
            'tab' => $tab,
            'tabs' => $allTabs,
            'pageData' => $pageData
        ));
    }

    /**
     * @inheritdoc
     * @Route("/topic/{id}", name="yeskn_blog_show", requirements={"id": "[1-9]\d*"})
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
     * @Route("/topic/{tid}/comment/thumb_up", name="thumbup_comment", requirements={
     *     "cid": "[1-9]\d*",
     *     "tid": "[1-9]\d*"
     * })
     */
    public function thumbPostComment(Request $request)
    {
        $comment = $this->getDoctrine()->getRepository('YesknBlogBundle:Comment')
            ->findOneBy(['id' => $request->get('cid')]);

        if (empty($comment)) {
            return new JsonResponse(['ret' => 0, 'msg' => 'comment not exits']);
        }

        $user = $this->getUser();

        if (empty($user)) {
            return new JsonResponse(['ret' => 0, 'msg' => 'no login']);
        }

        if ($comment->getThumbUpUsers()->contains($user)) {
            $comment->removeThumbUpUser($user);
            $action = 0;
        } else {
            $comment->addThumbUpUser($user);
            $action = 1;
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return new JsonResponse(['ret' => 1, 'info' => $action]);
    }

    /**
     * 创建主题
     *
     * @Route("/topic/create", name="create_topic")
     */
    public function createPost(Request $request)
    {
        if ($request->isMethod('GET')) {

            $tabs = $this->getDoctrine()->getRepository('YesknBlogBundle:Tab')->findAll();

            return $this->render('@YesknBlog/create-topic.html.twig', [
                'tabs' => $tabs
            ]);
        }

        $title = strip_tags($request->get('title'));
        $content = $request->get('content');

        $content = strip_tags($content, '<blockquote><code><p><br><a><strong><span><i><u><strike><b><font>');

        $htmlPurer  = new HtmlPurer();
        $content = $htmlPurer->pure($content)->getResult();

        if (empty($title) or empty(strip_tags($content))) {
            return new JsonResponse(['ret' => 0, 'msg' => '内容为空!']);
        }

        $tab = $this->getDoctrine()->getRepository('YesknBlogBundle:Tab')
            ->findOneBy(['alias' => $request->get('tab')]);
        $post = new Post();

        $post->setTitle($title);
        $post->setContent($content);
        $post->setViews(mt_rand(1, 3));
        $post->setIsTop(false);
        $post->setAuthor($this->getUser());
        $post->setIsDeleted(false);
        $post->setExcerpt('');
        $post->setCover('');
        $post->setStatus('published');
        $post->setTab($tab);

        $date = new \DateTime();

        $post->setCreatedAt($date);
        $post->setUpdatedAt($date);

        $em = $this->getDoctrine()->getManager();

        $em->persist($post);
        $em->flush();

        return $this->redirectToRoute('yeskn_blog_show', ['id' => $post->getId()]);
    }

    /**
     * @Route("/topic/{postId}/comment/add", name="add_comment_to_post")
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

        $htmlPurer  = new HtmlPurer();
        $content = $htmlPurer->pure($content)->getResult();

        if (empty(strip_tags($content)) or mb_strlen($content) > 500) {
            return new JsonResponse(['ret' => 0, 'msg' => '内个啥...长度好像不合适哦！']);
        }

        /**
         * @var User $user
         */
        $user = $this->getUser();

        $cost = 1;

        if ($htmlPurer->hasColor()) {
            $cost = 5;
        }

        if ($user->getGold() < $cost) {
            return new JsonResponse(['ret' => 0, 'msg' => 'no gold']);
        }

        $comment = new Comment();

        $comment->setContent($content);
        $comment->setCreatedAt(new \DateTime());
        $comment->setPost($post);
        $comment->setUser($this->getUser());
        $comment->setReplyTo(0);

        $em->persist($comment);

        $user->setGold($user->getGold()-$cost);

        $post->setUpdatedAt(new \DateTime());

        $em->flush();

        if ($user->getId() != $post->getAuthor()->getId()) {
            $notice = new Notice();
            $notice->setObject($post);
            $notice->setCreatedAt(new \DateTime());
            $notice->setType(Notice::TYPE_COMMENT_POST);
            $notice->setIsRead(false);
            $notice->setPushTo($post->getAuthor());
            $notice->setCreatedBy($user);
            $notice->setContent($comment);

            $em->persist($notice);
        }

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

    /**
     * @Route("/buy-beer", methods={"GET"}, name="buy_beer")
     */
    public function buyBeerAction()
    {
        return $this->render('@YesknBlog/buy-beer.html.twig');
    }

    /**
     * @Route("/thanks", name="thanks")
     */
    public function thanks()
    {
        return $this->render('@YesknBlog/thanks.html.twig');
    }
}
