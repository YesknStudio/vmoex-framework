<?php

namespace Yeskn\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="footer_link")
 * @ORM\Entity(repositoryClass="Yeskn\MainBundle\Repository\FooterLinkRepository")
 */
class FooterLink
{
    const NAME = '底部链接';

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
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string")
     */
    private $link;

    /**
     * @var int
     * @ORM\Column(name="priority", type="smallint", options={"default": 0})
     */
    private $priority;

    /**
     * @var boolean
     * @ORM\Column(name="is_pjax", type="boolean")
     */
    private $isPjax;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return bool
     */
    public function isPjax()
    {
        return $this->isPjax;
    }

    /**
     * @param bool $isPjax
     */
    public function setIsPjax($isPjax)
    {
        $this->isPjax = $isPjax;
    }
}
