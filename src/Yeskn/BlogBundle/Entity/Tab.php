<?php
/**
 * This file is part of project Vmoex.
 *
 * Author: Jake
 * Create: 2018-06-02 23:09:35
 */

namespace Yeskn\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Tab
 * @package Yeskn\BlogBundle\Entity
 * @ORM\Table(name="tab")
 * @ORM\Entity(repositoryClass="Yeskn\BlogBundle\Repository\TabRepository")
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
     * @ORM\OneToMany(targetEntity="Yeskn\BlogBundle\Entity\Post", mappedBy="tab")
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
     * @param \Yeskn\BlogBundle\Entity\Post $post
     *
     * @return Tab
     */
    public function addPost(\Yeskn\BlogBundle\Entity\Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \Yeskn\BlogBundle\Entity\Post $post
     */
    public function removePost(\Yeskn\BlogBundle\Entity\Post $post)
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
}
