<?php
/**
 * This file is part of project JetBlog.
 *
 * Author: Jake
 * Create: 2018-05-27 01:35:52
 */

namespace Yeskn\BlogBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Yeskn\BlogBundle\Entity\Chat;

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