<?php

/**
 * This file is part of project project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-14 16:13:28
 */

namespace Yeskn\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Yeskn\MainBundle\Entity\Post;
use Yeskn\MainBundle\Entity\Tab;

class PostRepository extends EntityRepository
{
    use RepositoryTrait;
    /**
     * @param Tab $tab
     * @param $sort
     * @param $pageSize
     * @param $first
     * @return Post[]
     */
    public function getIndexList($tab, $sort, $pageSize, $first)
    {
        $qb = $this->createQueryBuilder('p');

        if ($tab) {
            $qb->where('p.tab = :tab')->setParameter('tab', $tab);

            if ($tab->getLevel() == 1) {
                $subQuery = $this->getEntityManager()->getRepository('YesknMainBundle:Tab')
                    ->createQueryBuilder('t')
                    ->select('t.id')
                    ->where('t.parent = :parent')
                    ->andWhere('t.level = 2')
                    ->setParameter('parent', $tab)
                    ->getQuery()
                    ->getArrayResult()
                ;

                if ($subQuery) {
                    $qb->orWhere($qb->expr()->in('p.tab', array_column($subQuery, 'id')));
                }
            }
        }

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
                FROM YesknMainBundle:Post p
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
