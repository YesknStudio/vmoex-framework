<?php
/**
 * This file is part of project Vmoex.
 *
 * Author: Jake
 * Create: 2018-06-10 23:25:46
 */

namespace Yeskn\BlogBundle\Entity;

/**
 * Class Notification
 * @package Yeskn\BlogBundle\Entity
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity(repositoryClass="Yeskn\BlogBundle\Repository\NotificationRepository")
 */
class Notification
{
    const TYPE_COMMENT_POST = 1;
    const TYPE_FAVORITE_POST = 2;
    const TYPE_THANKS_POST = 3;
    const TYPE_COMMENT_REPLAY = 4;
    const TYPE_THUMBUP_COMMENT = 5;
    const TYPE_BROADCAST = 6;
    const TYPE_NEW_FOLLOWER = 7;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="type", type="smallint")
     */
    private $type;

    /**
     * @var bool
     * @ORM\Column(name="is_read", type="boolean", options={"default":false})
     */
    private $isRead;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Yeskn\BlogBundle\Entity\User")
     * @ORM\JoinColumn(name="push_to", referencedColumnName="id")
     */
    private $pushTo;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Yeskn\BlogBundle\Entity\User")
     * @ORM\JoinColumn(name="push_from", referencedColumnName="id")
     */
    private $pushFrom;

    /**
     * @var
     * @ORM\OneToOne(targetEntity="Yeskn\BlogBundle\Entity\Comment")
     */
    private $content;

    /**
     * @var
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function isRead()
    {
        return $this->isRead;
    }

    /**
     * @param bool $isRead
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;
    }

    /**
     * @return User
     */
    public function getPushTo()
    {
        return $this->pushTo;
    }

    /**
     * @param User $pushTo
     */
    public function setPushTo(User $pushTo)
    {
        $this->pushTo = $pushTo;
    }

    /**
     * @return User
     */
    public function getPushFrom()
    {
        return $this->pushFrom;
    }

    /**
     * @param User $pushFrom
     */
    public function setPushFrom(User $pushFrom)
    {
        $this->pushFrom = $pushFrom;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }
}