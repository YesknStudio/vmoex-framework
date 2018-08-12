<?php

/*
 * This file is part of project yeskn/vmoex.
 *
 * (c) Jaggle <jaggle@yeskn.com>
 *
 * created at 2018-05-25 22:42:21
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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