<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-10-01 20:55:32
 */

namespace Yeskn\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Notice
 * @package Yeskn\MainBundle\Entity
 *
 * @ORM\Table(name="open_user")
 * @ORM\Entity(repositoryClass="Yeskn\MainBundle\Repository\OpenUserRepository")
 */
class OpenUser
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
     * @ORM\OneToOne(targetEntity="Yeskn\MainBundle\Entity\User")
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(name="github_node_id", type="string")
     */
    private $githubNodeId = '';

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
     * @return mixed
     */
    public function getGithubNodeId()
    {
        return $this->githubNodeId;
    }

    /**
     * @param mixed $githubNodeId
     */
    public function setGithubNodeId($githubNodeId)
    {
        $this->githubNodeId = $githubNodeId;
    }
}
