<?php

/**
 * This file is part of project project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-14 17:25:53
 */

namespace Yeskn\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class NoticeRepository extends EntityRepository
{
    use RepositoryTrait;

    public function getUnreadCount($userId)
    {
        try {
            return (int) $this->createQueryBuilder('p')
                ->select('COUNT(p)')
                ->where('p.pushTo = :userId')
                ->andWhere('p.isRead = false')
                ->setParameter('userId', $userId)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException $exception) {
            return 0;
        }
    }
}
