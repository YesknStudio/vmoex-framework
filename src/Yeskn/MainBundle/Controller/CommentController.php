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
use Yeskn\Support\Http\ApiFail;
use Yeskn\Support\Http\ApiOk;

/**
 * Class CommentController
 * @package Yeskn\MainBundle\Controller
 *
 * @Security("has_role('ROLE_USER')")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/topic/comment/thumb_up", name="thumb_up_comment")
     *
     * @param $request
     * @return JsonResponse
     */
    public function thumbPostComment(Request $request)
    {
        $cid = $request->get('cid');

        $comment = $this->getDoctrine()->getRepository('YesknMainBundle:Comment')
            ->find($cid);

        if (empty($comment)) {
            return new ApiFail($this->trans('comment_not_exist'));
        }

        $user = $this->getUser();

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

        return new ApiOk(['info' => $action]);
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
        $cost = 1;

        /** @var Post $post */
        $post = $this->getDoctrine()->getRepository('YesknMainBundle:Post')->find($postId);
        if (empty($post)) {
            return new ApiFail($this->trans('post_not_exist'));
        }

        $content = $request->get('content');
        $color = $request->get('color');

        $content = str_replace('&nbsp;', ' ', $content);
        $content = strip_tags($content, 'img');

        if (empty($content) or mb_strlen($content) > 500) {
            return new ApiFail($this->trans('length_not_support'));
        }

        if ($user->getGold() < $cost) {
            return new ApiFail( $this->trans('no_enough_gold'));
        }

        $mentioned = [];

        preg_match_all("/^(@.*?)\s/", $content, $matches);

        $mentioned = array_merge($mentioned, $matches[1]);

        preg_match_all("/\s(@.*?)\s/", $content, $matches);

        $mentioned = array_merge($mentioned, $matches[1]);

        preg_match_all("/\s(@.*?)$/", $content, $matches);

        $mentioned = array_merge($mentioned, $matches[1]);

        if (count($mentioned) != count(array_unique($mentioned))) {
            return new ApiFail($this->trans('do_not_repeat_mention_others'));
        }

        $userRepo = $this->getRepo('YesknMainBundle:User');

        $content = $parsedContent = $markdown->transformMarkdown($content);
        $content = preg_replace('/<p>(.*?)<\/p>/', '$1', $content);// 不需要markdown为我添加p标签！！！

        foreach ($mentioned as $item) {
            $username = trim($item, '@ ');

            /** @var User $findOne */
            $findOne = $userRepo->findOneBy(['username' => $username]);

            if (empty($findOne)) {
                continue;
            }

            $url = $this->generateUrl('member_home', ['username' => $username]);
            $content = str_replace($item, "<a data-pjax href='{$url}'>$item</a>", $content);

            $this->get(NoticeService::class)
                ->add($user, $findOne, Notice::TYPE_COMMENT_MENTION, $parsedContent, $post);
        }


        if ($color) {
            $content = "<span style='color:{$color}'>{$content}</span>";
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

        return new ApiOk();
    }
}
