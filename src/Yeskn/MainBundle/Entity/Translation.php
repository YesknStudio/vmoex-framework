<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-14 22:10:26
 */

namespace Yeskn\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="translation")
 * @ORM\Entity(repositoryClass="Yeskn\MainBundle\Repository\TranslationRepository")
 */
class Translation
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
     *
     * @ORM\Column(name="message_id")
     */
    private $messageId;

    /**
     * @var string
     *
     * @ORM\Column(name="chinese", nullable=true)
     */
    private $chinese;

    /**
     * @var string
     *
     * @ORM\Column(name="english", nullable=true)
     */
    private $english;

    /**
     * @var string
     *
     * @ORM\Column(name="japanese", nullable=true)
     */
    private $japanese;

    /**
     * @var string
     *
     * @ORM\Column(name="taiwanese", nullable=true)
     */
    private $taiwanese;

    /**
     * @var boolean
     *
     * @ORM\Column(name="can_delete", type="boolean", options={"default":false})
     */
    private $canDelete = true;

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
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * @param string $messageId
     */
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
    }

    /**
     * @return string
     */
    public function getChinese()
    {
        return $this->chinese;
    }

    /**
     * @param string $chinese
     */
    public function setChinese($chinese)
    {
        $this->chinese = $chinese;
    }

    /**
     * @return string
     */
    public function getEnglish()
    {
        return $this->english;
    }

    /**
     * @param string $english
     */
    public function setEnglish($english)
    {
        $this->english = $english;
    }

    /**
     * @return string
     */
    public function getJapanese()
    {
        return $this->japanese;
    }

    /**
     * @param string $japanese
     */
    public function setJapanese($japanese)
    {
        $this->japanese = $japanese;
    }

    /**
     * @return string
     */
    public function getTaiwanese()
    {
        return $this->taiwanese;
    }

    /**
     * @param string $taiwanese
     */
    public function setTaiwanese($taiwanese)
    {
        $this->taiwanese = $taiwanese;
    }

    /**
     * @return bool
     */
    public function isCanDelete()
    {
        return $this->canDelete;
    }

    /**
     * @param bool $canDelete
     */
    public function setCanDelete($canDelete)
    {
        $this->canDelete = $canDelete;
    }
}
