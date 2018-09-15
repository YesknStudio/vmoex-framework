<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-15 13:11:29
 */

namespace Yeskn\MainBundle\Twig;

use Doctrine\ORM\EntityManager;

class WebsiteInfo extends \Twig_Extension
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    public function websiteInfo()
    {
        $res = $this->em->getRepository('YesknMainBundle:Manage')->findAll();

        $return = [];

        foreach ($res as $value) {
            $return[$value->getName()] = $value->getValue();
        }

        return $return;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('websiteInfo',array($this,'websiteInfo'),array('needs_environment' => true, 'is_safe' => 'html')),
        ];
    }
}