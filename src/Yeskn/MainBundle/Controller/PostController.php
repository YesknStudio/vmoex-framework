<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-14 18:06:58
 */

namespace Yeskn\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\MainBundle\Entity\Comment;
use Yeskn\MainBundle\Entity\Post;

/**
 * Class PostController
 * @package Yeskn\MainBundle\Controller
 *
 * @Route("/post")
 */
class PostController extends Controller
{
    /**
     * @Route("/", name="post_list")
     *
     * @param $request
     * @return Response
     */
    public function postListAction(Request $request)
    {
        $tab = $request->get('tab');
        $page = $request->get('page', 1);

        $pagesize = 25;

        if (empty($tab)) {
            $tab = $request->cookies->get('_tab');
        }

        if (empty($tab)) {
            $tab = 'all';
        }

        $sort = ['updatedAt' => 'DESC'];

        if ($tab == 'hot') {
            $sort = ['views' => 'DESC'];
        }

        $tabObj = null;

        if ($tab and $tab != 'all' and $tab != 'hot') {
            $tabObj = $this->getDoctrine()->getRepository('YesknMainBundle:Tab')
                ->findOneBy(['alias' => $tab]);
            if (empty($tabObj)) {
                return new JsonResponse('tab not exists');
            }
        }

        $posts = $this->getDoctrine()->getRepository('YesknMainBundle:Post')
            ->getIndexList($sort, $pagesize, $pagesize*($page-1));

        $countQuery = $this->getDoctrine()->getRepository('YesknMainBundle:Post')
            ->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.isDeleted = false');

        if (!empty($tabObj)) {
            $countQuery->andWhere('a.tab = :tab')->setParameter('tab', $tabObj);

        }

        $count = $countQuery->getQuery()->getSingleScalarResult();

        $allTabs = $this->getDoctrine()->getRepository('YesknMainBundle:Tab')
            ->findBy(['level' => 1]);

        $pageData['allPage'] = ceil($count/$pagesize);
        $pageData['currentPage'] = $page;

        $response = $this->render('@YesknMain/post/index.html.twig', array(
            'posts' => $posts,
            'tab' => $tab,
            'currentTab' => $tabObj,
            'tabs' => $allTabs,
            'pageData' => $pageData
        ));

        if ($tabObj and $tabObj->getLevel() == 1) {
            $response->headers->setCookie(new Cookie('_tab', $tab));
        }

        if ($tab == 'all' or $tab == 'hot') {
            $response->headers->setCookie(new Cookie('_tab', $tab));
        }

        return $response;
    }

    /**
     * 查看文章详情
     * @Route("/{id}", name="post_show", requirements={"id": "[1-9]\d*"})
     *
     * @param $id
     * @return Response
     */
    public function postShowAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        /** @var Post $post */
        $post = $this->getDoctrine()->getRepository('YesknMainBundle:Post')->find($id);
        if (empty($post)) {
            return $this->render('@YesknMain/error.html.twig', [
                'message' => '文章不存在'
            ]);
        }
        $post->setViews(intval($post->getViews())+1);
        $em->flush();

        $commentUsers = [];
        /**
         * @var Comment $comment
         */
        foreach ($post->getComments() as $comment) {
            $name = $comment->getUser()->getNickname();
            if (array_search($name, $commentUsers) === false) {
                $commentUsers[] = $name;
            }
        }

        $response = $this->render('@YesknMain/post/show.html.twig', array(
            'post' => $post,
            'commentUsers' => json_encode($commentUsers)
        ));

        return $response;
    }

    /**
     * 创建主题
     *
     * @Route("/create", name="create_post")
     *
     * @param $request
     * @return RedirectResponse|Response
     */
    public function createPost(Request $request)
    {
        if ($request->isMethod('GET')) {

            $tabs = $this->getDoctrine()->getRepository('YesknMainBundle:Tab')->findBy(['level' => 2]);

            return $this->render('@YesknMain/post/create.html.twig', [
                'tabs' => $tabs
            ]);
        }

        $title = strip_tags($request->get('title'));
        $content = $request->get('content');

        $content = strip_tags($content);

        $content = nl2br($content);

        if (empty($title) or empty(strip_tags($content))) {
            return new JsonResponse(['ret' => 0, 'msg' => '内容为空!']);
        }

        $tab = $this->getDoctrine()->getRepository('YesknMainBundle:Tab')
            ->findOneBy(['alias' => $request->get('tab')]);
        $post = new Post();

        $post->setTitle($title);
        $post->setContent($content);
        $post->setViews(mt_rand(1, 3));
        $post->setIsTop(false);
        $post->setAuthor($this->getUser());
        $post->setIsDeleted(false);
        $post->setSummary('');
        $post->setCover('');
        $post->setTab($tab);
        $post->setStatus('published');

        $date = new \DateTime();

        $post->setCreatedAt($date);
        $post->setUpdatedAt($date);

        $em = $this->getDoctrine()->getManager();

        $em->persist($post);
        $em->flush();

        return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
    }
}