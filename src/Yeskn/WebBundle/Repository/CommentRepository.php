<?php
/**
 * This file is part of project Vmoex.
 *
 * Author: Jake
 * Create: 2018-05-25 22:42:21
 */

namespace Yeskn\WebBundle\Repository;

use Yeskn\CommonBundle\BaseRepository;

class CommentRepository extends BaseRepository
{
    /**
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countComment()
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}