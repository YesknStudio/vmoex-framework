<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-22 13:49:40
 */

namespace Yeskn\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="blog")
 * @ORM\Entity(repositoryClass="Yeskn\MainBundle\Repository\BlogRepository")
 */
class Blog
{
    const STATUS_STARING = 'starting';
    const STATUS_QUEUEING = 'queueing';
    const STATUS_CREATED = 'created';

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
     * @ORM\ManyToOne(targetEntity="Yeskn\MainBundle\Entity\User")
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(name="subdomain", type="string", nullable=true)
     */
    private $subdomain;

    /**
     * @var string
     * @ORM\Column(name="password", type="string", nullable=true)
     */
    private $password;

    /**
     * @var string
     * @ORM\Column(name="cname", type="string", nullable=true)
     */
    private $cname;


    /**
     * @var Device[]
     * @ORM\OneToMany(targetEntity="Yeskn\MainBundle\Entity\Device", mappedBy="blog")
     */
    private $devices;

    /**
     * @var string
     * @ORM\Column(name="title", type="string")
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="subtitle", type="text", nullable=true)
     */
    private $subtitle;

    /**
     * @var File
     * @ORM\Column(name="cover", type="string", nullable=true)
     * @Assert\File()
     */
    private $cover = 'https://static.yeskn.com/wpcast/default-img.png';

    /**
     * @var string
     * @ORM\Column(name="status", type="string")
     */
    private $status = 'starting';

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

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
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getSubdomain()
    {
        return $this->subdomain;
    }

    /**
     * @param string $subdomain
     */
    public function setSubdomain($subdomain)
    {
        $this->subdomain = $subdomain;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getCname()
    {
        return $this->cname;
    }

    /**
     * @param string $cname
     */
    public function setCname($cname)
    {
        $this->cname = $cname;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * @param string $subtitle
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }

    /**
     * @return File
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * @param File $cover
     */
    public function setCover($cover)
    {
        $this->cover = $cover;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return Device[]
     */
    public function getDevices()
    {
        return $this->devices;
    }

    /**
     * @param Device[] $devices
     */
    public function setDevices($devices)
    {
        $this->devices = $devices;
    }
}