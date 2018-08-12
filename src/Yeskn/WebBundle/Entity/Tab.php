<?php

/*
 * This file is part of project yeskn/vmoex.
 *
 * (c) Jaggle <jaggle@yeskn.com>
 *
 * created at 2018-06-02 23:09:35
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yeskn\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Tab
 * @package Yeskn\WebBundle\Entity
 * @ORM\Table(name="tab")
 * @ORM\Entity(repositoryClass="Yeskn\WebBundle\Repository\TabRepository")
 */
class Tab
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
     * @ORM\Column(name="name", type="string", length=20)
     */
    private $name;

    /**
     * @ORM\Column(name="alias", type="string", length=20)
     */
    private $alias;

    /**
     * @ORM\Column(name="level", type="smallint", options={"default":1})
     */
    private $level;

    /**
     * @var Tab
     * @ORM\ManyToOne(targetEntity="Yeskn\WebBundle\Entity\Tab", inversedBy="id")
     */
    private $parent;

    /**
     * @ORM\Column(name="description", type="text", options={"default":""})
     */
    private $description;

    /**
     * @ORM\Column(name="avatar", type="string", length=200)
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity="Yeskn\WebBundle\Entity\Post", mappedBy="tab")
     */
    private $posts;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return Tab
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add post
     *
     * @param \Yeskn\WebBundle\Entity\Post $post
     *
     * @return Tab
     */
    public function addPost(\Yeskn\WebBundle\Entity\Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \Yeskn\WebBundle\Entity\Post $post
     */
    public function removePost(\Yeskn\WebBundle\Entity\Post $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Set alias
     *
     * @param string $alias
     *
     * @return Tab
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return Tab
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Tab $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }
}
