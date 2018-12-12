<?php

/**
 * This file is part of project project yeskn-studio/vmoex-framework.
 *
 * Author: Jaggle
 * Create: 2018-09-14 17:23:03
 */

namespace Yeskn\MainBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Knp\Bundle\MarkdownBundle\Parser\MarkdownParser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\MainBundle\Entity\Comment;
use Yeskn\MainBundle\Entity\Notice;
use Yeskn\MainBundle\Entity\Post;
use Yeskn\MainBundle\Entity\User;
use Yeskn\MainBundle\Services\NoticeService;
use Yeskn\Support\AbstractController;

/**
 * Class CommentController
 * @package Yeskn\MainBundle\Controller
 *
 * @Security("has_role('ROLE_USER')")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/topic/comment/thumb_up", name="thumb_up_comment", requirements={
     *     "cid": "[1-9]\d*",
     * })
     *
     * @param $request
     * @return JsonResponse
     */
    public function thumbPostComment(Request $request)
    {
        $comment = $this->getDoctrine()->getRepository('YesknMainBundle:Comment')
            ->findOneBy(['id' => $request->get('cid')]);

        if (empty($comment)) {
            return new JsonResponse(['ret' => 0, 'msg' => 'comment not exits']);
        }

        $user = $this->getUser();

        if (empty($user)) {
            return new JsonResponse(['ret' => 0, 'msg' => 'no login']);
        }

        /** @var ArrayCollection $thumbUsers */
        $thumbUsers = $comment->getThumbUpUsers();

        if ($thumbUsers->contains($user)) {
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
     * @param $markdown
     * @return JsonResponse
     */
    public function addCommentToPostAction(Request $request, $postId, MarkdownParser $markdown)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        /** @var Post $post */
        $post = $this->getDoctrine()->getRepository('YesknMainBundle:Post')->find($postId);
        if (empty($post)) {
            return new JsonResponse(['err' => '文章不存在']);
        }

        $content = $request->get('content');
        $content = strip_tags($content);

        if (empty($content) or mb_strlen($content) > 500) {
            return new JsonResponse(['ret' => 0, 'msg' => '内个啥...长度好像不合适哦！']);
        }

        $mentioned = [];

        preg_match_all("/^(@.*?)\s/", $content, $matches);

        $mentioned = array_merge($mentioned, $matches[1]);

        preg_match_all("/\s(@.*?)\s/", $content, $matches);

        $mentioned = array_merge($mentioned, $matches[1]);

        preg_match_all("/\s(@.*?)$/", $content, $matches);

        $mentioned = array_merge($mentioned, $matches[1]);

        if (count($mentioned) != count(array_unique($mentioned))) {
            return new JsonResponse(['ret' => 0, 'msg' => '请勿重复@其他人！']);
        }

        $userRepo = $this->getRepo('YesknMainBundle:User');

        $content = $parsedContent = $markdown->transformMarkdown($content);

        foreach ($mentioned as $item) {
            $username = trim($item, '@');

            /** @var User $findOne */
            $findOne = $userRepo->findOneBy(['username' => $username]);

            if (empty($findOne)) {
                continue;
            }

            $url = $this->generateUrl('member_home', ['username' => $username]);
            $content = str_replace($item, "<a data-pjax href='{$url}'>$item</a>", $content);

            $this->get(NoticeService::class)->add($user, $findOne, Notice::TYPE_COMMENT_MENTION, $parsedContent, $post);
        }

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
