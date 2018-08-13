<?php

/*
 * This file is part of project yeskn/vmoex.
 *
 * (c) Jaggle <jaggle@yeskn.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yeskn\WebBundle\Repository;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\NoResultException;
use Yeskn\WebBundle\Entity\Active;
use Yeskn\WebBundle\Entity\User;

class ActiveRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $user
     * @return Active
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function increaseTodayActive(User $user)
    {
        $datetime = new \DateTime();

        /**
         * @var Active $active
         */
        try {
            $active = $this->createQueryBuilder('p')
                ->select('p')
                ->where('p.user = :user')->setParameter('user', $user)
                ->andWhere('p.date = :date')->setParameter('date', date('Y-m-d'))
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $exception) {
            $active = new Active();

            $active->setUser($user);
            $active->setDate($datetime);
            $active->setVal(0);
            $active->setCreatedAt($datetime);

            $user->setActiveVal(0);

            $this->getEntityManager()->persist($active);
        }

        $active->setVal($active->getVal()+1);
        $active->setUpdatedAt($datetime);

        $user->setActiveVal($user->getActiveVal()+1);

        $this->getEntityManager()->flush();

        return $active;
    }

    /**
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countOnlineUser()
    {
        $datetime = new \DateTime('-6 hour');

        try {
            return (int) $this->createQueryBuilder('p')
                ->select('COUNT(p.id)')
                ->where('p.updatedAt >= :update')
                ->setParameter('update', $datetime, Type::DATETIME)
                ->distinct()
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException $exception) {
            return 0;
        }

    }
}
