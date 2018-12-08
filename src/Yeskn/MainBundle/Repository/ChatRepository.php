<?php

/**
 * This file is part of project project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-14 17:28:08
 */

namespace Yeskn\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Yeskn\MainBundle\Entity\Chat;

class ChatRepository extends EntityRepository
{
    /**
     * @param $count
     * @return Chat[]
     */
    public function getLatestChat($count)
    {
        $results = $this->createQueryBuilder('p')
            ->select('p')
            ->setMaxResults($count)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

        return array_reverse($results);
    }
}
