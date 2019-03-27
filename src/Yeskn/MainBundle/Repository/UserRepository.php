<?php

/**
 * This file is part of project project yeskn-studio/vmoex-framework.
 *
 * Author: Jaggle
 * Create: 2018-08-30 18:06:25
 */

namespace Yeskn\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
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
     * @param $userId
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function checkEmailAndUsername($email, $username, $userId = null)
    {
        $query = $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->where('1=1');

        if ($userId) {
            $query->andWhere('p.id != :userId')->setParameter('userId', $userId);
        }

        $or = $query->expr()->orx();
        $or->add($query->expr()->eq('p.username', "'{$username}'"));
        $or->add($query->expr()->eq('p.email', "'{$email}'"));

        $query->andWhere($or);

        return $query->getQuery()
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
