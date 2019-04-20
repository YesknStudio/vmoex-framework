<?php
/**
 * This file is part of project project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-05-27 04:27:16
 */

namespace Yeskn\MainBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="Yeskn\MainBundle\Repository\MessageRepository")
 */
class Message
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Yeskn\MainBundle\Entity\User")
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $sender;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Yeskn\MainBundle\Entity\User")
     * @ORM\JoinColumn(name="receiver_id" , referencedColumnName="id", onDelete="CASCADE")
     */
    private $receiver;

    /**
     * @var
     * @ORM\Column(name="content", type="string", length=250)
     */
    private $content;

    /**
     * @var boolean
     * @ORM\Column(name="is_read", type="boolean")
     */
    private $isRead = false;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Message
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set isRead
     *
     * @param boolean $isRead
     *
     * @return Message
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * Get isRead
     *
     * @return boolean
     */
    public function getIsRead()
    {
        return $this->isRead;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Message
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set sender
     *
     * @param \Yeskn\MainBundle\Entity\User $sender
     *
     * @return Message
     */
    public function setSender(User $sender = null)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender
     *
     * @return \Yeskn\MainBundle\Entity\User
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set receiver
     *
     * @param \Yeskn\MainBundle\Entity\User $receiver
     *
     * @return Message
     */
    public function setReceiver(User $receiver = null)
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * Get receiver
     *
     * @return \Yeskn\MainBundle\Entity\User
     */
    public function getReceiver()
    {
        return $this->receiver;
    }
}
