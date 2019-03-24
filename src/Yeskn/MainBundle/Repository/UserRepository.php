<?php

/**
 * This file is part of project project yeskn-studio/vmoex-framework.
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
    use RepositoryTrait;

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

    /**
     * @param $word
     * @param int $cursor
     * @param int $limit
     * @return array
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function queryUser($word, $cursor = 0, $limit = 15)
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.username LIKE :username')->setParameter('username', "%$word%")
            ->orWhere('p.nickname LIKE :nickname')->setParameter('nickname', "%$word%");

        $total = $qb->select('COUNT(p)')->getQuery()->getSingleScalarResult();

        $results = $qb->select('p')
            ->orderBy('p.gold', 'DESC')
            ->setFirstResult($cursor)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return [$results, $total];
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countUser()
    {
        return $this->createQueryBuilder('p')
            ->select('MAX(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getTodayLoggedUserCount()
    {
        $count = $this->createQueryBuilder('p')
            ->select('SUM(p.id)')
            ->where('p.loginAt >= :today')
            ->setParameter('today', new \DateTime('today'))
            ->getQuery()
            ->getSingleScalarResult();

        return $count ?: 0;
    }
}
