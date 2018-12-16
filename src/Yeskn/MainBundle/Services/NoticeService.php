<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-10-18 22:05:39
 */

namespace Yeskn\MainBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Yeskn\MainBundle\Entity\Message;
use Yeskn\MainBundle\Entity\Notice;
use Yeskn\MainBundle\Entity\User;
use Yeskn\MainBundle\Twig\Render;
use Yeskn\Support\AbstractService;

class NoticeService extends AbstractService
{
    private $pushService;
    private $mailer;
    private $render;
    private $emailUser;

    public function __construct(EntityManagerInterface $em
        , SocketPushService $pushService
        , \Swift_Mailer $mailer
        , Render $render
        , $emailUser
    ) {
        parent::__construct($em);

        $this->pushService = $pushService;
        $this->mailer = $mailer;
        $this->render = $render;
        $this->emailUser = $emailUser;
    }


    private function convertType2Event($type)
    {
        $events = [
            Notice::TYPE_COMMENT_MENTION => 'comment_mention',
            Notice::TYPE_COMMENT_POST => 'comment_post',
        ];

        return $events[$type];
    }

    private function getMessageByEvent($event)
    {
        $messages = [
            'comment_mention' => '有人在评论中提到了你，请到个人中心中查看。',
            'comment_post' => '有人评论了你的文章，请到个人中心中查看。'
        ];

        return $messages[$event];
    }

    /**
     * 添加一条通知
     *
     * @param User $creator
     * @param User $pushTo
     * @param $type
     * @param $content
     * @param $object
     */
    public function add(User $creator, User $pushTo, $type, $content, $object)
    {
        $notice = $this->databaseNotice($creator,  $pushTo, $type, $content, $object);

        $this->pushNotice($notice, $pushTo);
        $this->emailNotice($pushTo->getEmail(), $notice);
    }

    public function databaseNotice(User $creator, User $pushTo, $type, $content, $object)
    {
        $notice = new Notice();

        $notice->setType($type);
        $notice->setCreatedBy($creator);
        $notice->setPushTo($pushTo);
        $notice->setIsRead(false);
        $notice->setCreatedAt(new \DateTime());
        $notice->setRowContent($content);
        $notice->setObject($object);

        $this->em->persist($notice);
        $this->em->flush();

        return $notice;
    }

    public function emailNotice($email, Notice $notice)
    {
        $message = (new \Swift_Message('Vmoex Notice'))
            ->setFrom($this->emailUser)
            ->setTo($email)
            ->setBody($this->render->renderEmailNotice($notice), 'text/html');

        $this->mailer->send($message);
    }

    public function emailMessage($email, Message $message)
    {
        $message = (new \Swift_Message('Vmoex通知'))
            ->setFrom($this->emailUser)
            ->setTo($email)
            ->setBody($this->render->renderEmailMessage($message), 'text/html');

        $this->mailer->send($message);
    }

    public function pushNotice(Notice $notice, User $pushTo)
    {
        $event = $this->convertType2Event($notice->getType());

        $this->pushService->push($pushTo->getUsername(), $event, [
            'message' => $this->getMessageByEvent($event)
        ]);
    }
}
