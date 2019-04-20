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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Yeskn\MainBundle\Entity\User;
use Yeskn\MainBundle\Entity\Visit;
use Yeskn\Support\AbstractControllerListener;

class ControllerListener extends AbstractControllerListener
{
    static $increasedTodayActive = false;

    private $firewallMap;

    private function addVisitRecord(Request $request)
    {
        $visit = new Visit();

        $visit->setIp($request->getClientIp());
        $visit->setAgent($request->headers->get('User-Agent'));
        $visit->setPath($request->getUri());
        $visit->setCreatedAt(new \DateTime());

        $this->em->persist($visit);
        $this->em->flush();
    }

    public function __construct(TokenStorageInterface $tokenStorage
        , EntityManagerInterface $em
        , FirewallMap $firewallMap
    ) {
        parent::__construct($tokenStorage, $em);
        $this->firewallMap = $firewallMap;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controllerName = $event->getRequest()->attributes->get('_controller');
        $config = $this->firewallMap->getFirewallConfig($event->getRequest());

        if (strpos($controllerName, 'Yeskn\MainBundle') !== 0
            || $config->isSecurityEnabled() === false
        ) {
            return ;
        }

        $this->addVisitRecord($event->getRequest());

        /** @var User $user */
        $user = $this->getUser();

        if ($user && self::$increasedTodayActive === false) {
            self::$increasedTodayActive = true;
            $this->em->getRepository('YesknMainBundle:Active')->increaseTodayActive($user);
        }
    }
}
