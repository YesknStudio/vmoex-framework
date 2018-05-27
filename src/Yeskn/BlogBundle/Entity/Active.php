<?php
/**
 * This file is part of project JetBlog.
 *
 * Author: Jake
 * Create: 2018-05-27 11:09:15
 */

namespace Yeskn\BlogBundle\Entity;

/**
 * @ORM\Table(name="active")
 * @ORM\Entity(repositoryClass="Yeskn\BlogBundle\Repository\ActiveRepository")
 */
class Active
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    private $val;

    private $user;

    private $date;

    private $createdAt;

    private $updatedAt;
}