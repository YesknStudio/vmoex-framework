<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-15 13:11:29
 */

namespace Yeskn\MainBundle\Twig;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class WebsiteInfo extends \Twig_Extension
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var null|Request
     */
    private $request;

    public function __construct(EntityManagerInterface $em, RequestStack $request)
    {
        $this->em = $em;
        $this->request = $request->getCurrentRequest();
    }

    public function websiteInfo()
    {
        static $return = [];

        if (empty($return)) {
            $res = $this->em->getRepository('YesknMainBundle:Manage')->findAll();

            $return = [];

            foreach ($res as $value) {
                $return[$value->getName()] = $value->getValue();
            }

            $announce = $this->em->getRepository('YesknMainBundle:Announce')
                ->findOneBy(['show' => 1], ['id' => 'DESC']);

            $return['announce'] = $announce;
        }

        return $return;
    }

    public function hideAnnounceAlert()
    {
        return (boolean) $this->request->cookies->has('_hide_announce');
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('websiteInfo', [$this, 'websiteInfo']),
            new \Twig_SimpleFunction('hideAnnounceAlert', [$this,'hideAnnounceAlert']),
        ];
    }
}
