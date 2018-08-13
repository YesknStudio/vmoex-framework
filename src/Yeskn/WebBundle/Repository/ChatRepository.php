<?php

/*
 * This file is part of project yeskn/vmoex.
 *
 * (c) Jaggle <jaggle@yeskn.com>
 *
 * created at 2018-05-27 01:35:52
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yeskn\WebBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Yeskn\WebBundle\Entity\Chat;

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