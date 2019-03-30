<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-10-18 22:51:48
 */

namespace Yeskn\MainBundle\Twig;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Twig\Extension\AbstractExtension;
use Yeskn\MainBundle\Entity\Message;
use Yeskn\MainBundle\Entity\Notice;

class Render extends AbstractExtension
{
    private $template;

    public function __construct(EngineInterface $template)
    {
        $this->template = $template;
    }

    public function renderEmailNotice(Notice $notice)
    {
        switch ($notice->getType()) {
            case Notice::TYPE_COMMENT_POST:
                return $this->template->render('emails/new-comment.html.twig', ['notice' => $notice]);
            case Notice::TYPE_COMMENT_MENTION:
                return $this->template->render('emails/mention-comment.html.twig', ['notice' => $notice]);
        }

        throw new \Exception('un-support notice type ' . $notice->getType());
    }

    public function renderEmailMessage(Message $message)
    {
        return $this->template->render('emails/new-message.html.twig', ['message' => $message]);
    }

    public function renderNoticeItem(Notice $notice)
    {
        switch ($notice->getType()) {
            case Notice::TYPE_COMMENT_POST:
                return $this->template->render('@YesknMain/user/notices/comment-post.html.twig', ['notice' => $notice]);
            case Notice::TYPE_COMMENT_MENTION:
                return $this->template->render('@YesknMain/user/notices/mention-comment.html.twig', ['notice' => $notice]);
        }

        throw new \Exception('un-support notice type ' . $notice->getType());
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('render_notice_item', array($this,'renderNoticeItem')),
        );
    }
}
