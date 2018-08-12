<?php
/**
 * This file is part of project Vmoex.
 *
 * Author: Jake
 * Create: 2018-05-27 04:58:26
 */

namespace Yeskn\BlogBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Yeskn\BlogBundle\Entity\Message;

class MessageRepository extends EntityRepository
{
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