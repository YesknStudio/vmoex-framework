<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-05-27 15:38:34
 */

namespace Yeskn\MainBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Yeskn\Support\AbstractControllerListener;

class ControllerListener extends AbstractControllerListener
{
    static $increasedTodayActive = false;

    private $firewallMap;

    public function __construct(TokenStorageInterface $tokenStorage
        , EntityManagerInterface $em
        , FirewallMap $firewallMap
    ) {
        parent::__construct($tokenStorage, $em);
        $this->firewallMap = $firewallMap;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
    }
}
