<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jaggle
 * Create: 2018-09-14 17:23:03
 */

namespace Yeskn\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\MainBundle\Entity\Comment;
use Yeskn\MainBundle\Entity\Notice;
use Yeskn\MainBundle\Entity\Post;
use Yeskn\MainBundle\Entity\User;

class CommentController extends Controller
{
    /**
     * @Route("/topic/comment/thumb_up", name="thumbup_comment", requirements={
     *     "cid": "[1-9]\d*",
     * })
     *
     * @param $request
     * @return JsonResponse
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
     * @Route("/topic/{postId}/comment/add", name="add_comment_to_post")
     *
     * @param $request
     * @param $postId
     * @return JsonResponse
     */
    public function addCommentToPostAction(Request $request, $postId)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Post $post */
        $post = $this->getDoctrine()->getRepository('YesknBlogBundle:Post')->find($postId);
        if (empty($post)) {
            return new JsonResponse(['err' => '文章不存在']);
        }

        $content = $request->get('content');

        $content = strip_tags($content);

        $content = str_replace('<p></p>', '', $content);

        if (empty(strip_tags($content)) or mb_strlen($content) > 500) {
            return new JsonResponse(['ret' => 0, 'msg' => '内个啥...长度好像不合适哦！']);
        }

        /**
         * @var User $user
         */
        $user = $this->getUser();

        $cost = 1;

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
}