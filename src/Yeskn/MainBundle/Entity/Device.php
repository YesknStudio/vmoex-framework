<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-29 17:36:21
 */

namespace Yeskn\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="device",
 *     uniqueConstraints={
 *       @ORM\UniqueConstraint(name="idx_unique_device", columns={"machine_id", "device_name"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="Yeskn\MainBundle\Repository\DeviceRepository")
 */
class Device
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
     * @var
     * @ORM\ManyToOne(targetEntity="Yeskn\MainBundle\Entity\Machine", inversedBy="devices")
     */
    private $machine;

    /**
     * @var string
     * @ORM\Column(name="device_name", type="string")
     */
    private $deviceName;

    /**
     * @var
     * @ORM\Column(name="type", type="string", options={"comment":"web、博客空间；db、数据库空间", "default":""})
     */
    private $type = '';

    /**
     * @var Blog
     * @ORM\ManyToOne(targetEntity="Yeskn\MainBundle\Entity\Blog", inversedBy="devices")
     * @ORM\JoinColumn(nullable=true)
     */
    private $blog;

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
     * @return mixed
     */
    public function getMachine()
    {
        return $this->machine;
    }

    /**
     * @param mixed $machine
     */
    public function setMachine($machine)
    {
        $this->machine = $machine;
    }

    /**
     * @return string
     */
    public function getDeviceName()
    {
        return $this->deviceName;
    }

    /**
     * @param string $deviceName
     */
    public function setDeviceName($deviceName)
    {
        $this->deviceName = $deviceName;
    }

    /**
     * @return Blog
     */
    public function getBlog()
    {
        return $this->blog;
    }

    /**
     * @param Blog $blog
     */
    public function setBlog($blog)
    {
        $this->blog = $blog;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}