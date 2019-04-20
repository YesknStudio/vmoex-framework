<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2019-04-20 18:53:51
 */

namespace Yeskn\MainBundle\EventListener;

use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Yeskn\MainBundle\Entity\User;
use Yeskn\MainBundle\Entity\Visit;
use Yeskn\Support\AbstractControllerListener;

class TerminateListener extends AbstractControllerListener
{
    static $increasedTodayActive = false;

    private $firewallMap;

    private function addVisitRecord(Request $request, $user)
    {
        $visit = new Visit();

        $visit->setIp($request->getClientIp());
        $visit->setAgent($request->headers->get('User-Agent'));
        $visit->setPath(urldecode($request->getUri()));
        $visit->setCreatedAt(new \DateTime());

        if ($user instanceof User) {
            $visit->setUser($user);
        }

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


    public function onTerminate(PostResponseEvent $event)
    {
        $controllerName = $event->getRequest()->attributes->get('_controller');

        $config = $this->firewallMap->getFirewallConfig($event->getRequest());

        if (strpos($controllerName, 'Yeskn\MainBundle') !== 0
            || $config->isSecurityEnabled() === false
        ) {
            return ;
        }

        /** @var User $user */
        $user = $this->getUser();

        $this->addVisitRecord($event->getRequest(), $user);

        if ($user && self::$increasedTodayActive === false) {
            self::$increasedTodayActive = true;
            $this->em->getRepository('YesknMainBundle:Active')->increaseTodayActive($user);
        }
    }
}
