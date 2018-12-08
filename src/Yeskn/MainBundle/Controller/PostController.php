<?php

/**
 * This file is part of project project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-14 18:06:58
 */

namespace Yeskn\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        $sortBy = $request->get('sortBy', 'com');

        return $this->forward('YesknMainBundle:Common:homeList', [], [
            'tab' => $tab,
            'page' => $page,
            'scope' => 'post',
            'sortBy' => $sortBy
        ]);
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

        $commentUsers = [$post->getAuthor()->getUsername()];
        /**
         * @var Comment $comment
         */
        foreach ($post->getComments() as $comment) {
            $name = $comment->getUser()->getUsername();
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
