<?php

/**
 * This file is part of project project yeskn-studio/vmoex-framework.
 *
 * Author: Jaggle
 * Create: 2018-09-14 17:08:27
 */

namespace Yeskn\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Yeskn\MainBundle\Entity\Message;

class MessageRepository extends EntityRepository
{
    use RepositoryTrait;

    /**
     * @param $user
     * @return Message[]
     */
    public function getUnReadMessages($user)
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.receiver = :receiver')->setParameter('receiver', $user)
            ->andWhere('p.isRead = false')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
