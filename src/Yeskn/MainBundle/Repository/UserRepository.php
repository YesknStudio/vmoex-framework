<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jaggle
 * Create: 2018-08-30 18:06:25
 */

namespace Yeskn\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Yeskn\MainBundle\Entity\User;

class UserRepository extends EntityRepository implements UserLoaderInterface
{
    public function loadUserByUsername($username)
    {
        return $this->loadUserByUsernameOrEmail($username);
    }

    /**
     * @param $email
     * @param $username
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function checkEmailAndUsername($email, $username)
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->where('p.email = :email')->setParameter('email', $email)
            ->orWhere('p.username = :username')->setParameter('username', $username)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param $usernameOrEmail
     * @return User|object
     */
    public function loadUserByUsernameOrEmail($usernameOrEmail)
    {
        if (preg_match('/^.+\@\S+\.\S+$/', $usernameOrEmail)) {
            $user = $this->findUserByEmail($usernameOrEmail);
            if (null !== $user) {
                return $user;
            }
        }

        return $this->findUserByUsername($usernameOrEmail);
    }

    public function findUserByEmail($email)
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function findUserByUserName($username)
    {
        return $this->findOneBy(['username' => $username]);
    }
}