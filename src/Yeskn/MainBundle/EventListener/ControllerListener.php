<?php
/**
 * This file is part of project JetBlog.
 *
 * Author: Jake
 * Create: 2018-05-27 15:38:34
 */

namespace Yeskn\MainBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Yeskn\MainBundle\Entity\User;
use Yeskn\MainBundle\Repository\ActiveRepository;

class ControllerListener
{
    private $tokenStorage;

    /**
     * @var ActiveRepository
     */
    private $repository;

    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $em)
    {
        $this->tokenStorage = $tokenStorage;
        $this->repository = $em->getRepository('YesknMainBundle:Active');
    }

    public function onKernelController()
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user) {
            $this->repository->increaseTodayActive($user);
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