<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-14 16:13:28
 */

namespace Yeskn\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Yeskn\MainBundle\Entity\Post;

class PostRepository extends EntityRepository
{
    /**
     * @param $sort
     * @param $pageSize
     * @param $first
     * @return Post[]
     */
    public function getIndexList($sort, $pageSize, $first)
    {
        $qb = $this->createQueryBuilder('p');

        $qb->orderBy('p.isTop', 'DESC');
        $qb->addOrderBy('p.'.key($sort), current($sort));
        $qb->setFirstResult($first);
        $qb->setMaxResults($pageSize);
        return $qb->getQuery()->getResult();
    }
    /**
     * @return Post[]
     */
    public function queryLatest()
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT p
                FROM YesknBlogBundle:Post p
                WHERE p.createdAt <= :now
                ORDER BY p.createdAt ASC
            ')
            ->setParameter('now',new \DateTime())
            ->getResult()
            ;
    }

    public function testQuery()
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.author','o')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $word
     * @param int $cursor
     * @param int $limit
     * @return array
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function queryPosts($word, $cursor = 0, $limit = 15)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.title LIKE :title')->setParameter('title', "%$word%")
            ->orWhere('p.content LIKE :content')->setParameter('content', "%$word%");

        $total = $qb->select('COUNT(p)')->getQuery()->getSingleScalarResult();

        $results = $qb->select('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setFirstResult($cursor)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return [$results, $total];
    }

    /**
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countPost()
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->getQuery()
            ->getSingleScalarResult();
    }

}