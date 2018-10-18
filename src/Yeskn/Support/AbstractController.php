<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-10-13 10:14:24
 */

namespace Yeskn\Support;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\User\UserInterface;
use Yeskn\MainBundle\Entity\User;

class AbstractController extends Controller
{
    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     * @throws \LogicException
     */
    public function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @param $repo
     * @return \Doctrine\Common\Persistence\ObjectRepository
     * @throws \LogicException
     */
    public function getRepo($repo)
    {
        return $this->getDoctrine()->getRepository($repo);
    }

    /**
     * @return UserInterface|User
     * @throws \LogicException
     */
    public function getUser()
    {
        /** @var User|UserInterface $user */
        $user = parent::getUser();

        return $user;
    }
}
