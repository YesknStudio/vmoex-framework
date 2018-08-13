<?php

/*
 * This file is part of project yeskn/vmoex.
 *
 * (c) Jaggle <jaggle@yeskn.com>
 *
 * created at 2018-05-27 04:58:26
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yeskn\WebBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Yeskn\WebBundle\Entity\Message;

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