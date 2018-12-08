<?php

/**
 * This file is part of project project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-05-27 01:28:07
 */

namespace Yeskn\MainBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="chat")
 * @ORM\Entity(repositoryClass="Yeskn\MainBundle\Repository\ChatRepository")
 */
class Chat
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
     * @var string
     * @ORM\Column(name="content", type="string", length=50)
     */
    private $content;

    /**
     * @var User
     *
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @ORM\ManyToOne(targetEntity="Yeskn\MainBundle\Entity\User",inversedBy="chats")
     */
    private $user;

    /**
     * @var \DateTime
     *
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
     * @return Chat
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Chat
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
     * Set user
     *
     * @param \Yeskn\MainBundle\Entity\User $user
     *
     * @return Chat
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Yeskn\MainBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
