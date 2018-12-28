<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-05-27 15:38:34
 */

namespace Yeskn\MainBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Yeskn\MainBundle\Entity\User;

class ControllerListener
{
    static $increasedTodayActive = false;

    private $tokenStorage;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $em)
    {
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
    }

    public function onKernelController()
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user && self::$increasedTodayActive == false) {
            self::$increasedTodayActive = true;
            $this->em->getRepository('YesknMainBundle:Active')->increaseTodayActive($user);
        }
    }

    /**
     * @return UserInterface|null
     */
    protected function getUser()
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return null;
        }

        /** @var UserInterface $user */
        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return null;
        }

        return $user;
    }
}
