<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-29 17:36:13
 */

namespace Yeskn\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="machine")
 * @ORM\Entity(repositoryClass="Yeskn\MainBundle\Repository\MachineRepository")
 */
class Machine
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
     * @ORM\Column(name="ip", type="string")
     */
    private $ip;

    /**
     * @var string
     * @ORM\Column(name="hostname", type="string")
     */
    private $hostname;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Yeskn\MainBundle\Entity\Device", mappedBy="machine")
     */
    private $devices;

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
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * @param string $hostname
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
    }
}
