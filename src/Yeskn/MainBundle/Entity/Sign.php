<?php
/**
 * This file is part of project JetBlog.
 *
 * Author: Jake
 * Create: 2018-05-27 17:12:38
 */

namespace Yeskn\MainBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="sign")
 * @ORM\Entity(repositoryClass="Yeskn\MainBundle\Repository\SignRepository")
 */
class Sign
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
     * @ORM\ManyToOne(targetEntity="Yeskn\MainBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id" , referencedColumnName="id")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $user;

    /**
     * @var
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var
     * @ORM\Column(name="got_gold", type="integer")
     */
    private $gotGold;



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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Sign
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set gotGold
     *
     * @param integer $gotGold
     *
     * @return Sign
     */
    public function setGotGold($gotGold)
    {
        $this->gotGold = $gotGold;

        return $this;
    }

    /**
     * Get gotGold
     *
     * @return integer
     */
    public function getGotGold()
    {
        return $this->gotGold;
    }

    /**
     * Set user
     *
     * @param \Yeskn\MainBundle\Entity\User $user
     *
     * @return Sign
     */
    public function setUser(\Yeskn\MainBundle\Entity\User $user = null)
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
