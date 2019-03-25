<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2019-03-25 11:53:02
 */

namespace Yeskn\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="advertisement")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Yeskn\MainBundle\Repository\AdvertisementRepository")
 */
class Advertisement
{
    const NAME = '广告';

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
     * @ORM\Column(name="title", type="string", length=50)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var string
     * @ORM\Column(name="type", type="text", length=10, options={"default": "html"})
     */
    private $type;

    /**
     * @var string
     * @ORM\Column(name="location", type="text", length=10)
     */
    private $location;

    /**
     * @var boolean
     * @ORM\Column(name="enable", type="boolean", options={"default": true})
     */
    private $enable;

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
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return bool
     */
    public function isEnable()
    {
        return $this->enable;
    }

    /**
     * @param bool $enable
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;
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
}
