<?php

/**
 * This file is part of project project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-14 16:13:28
 */

namespace Yeskn\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Yeskn\MainBundle\Entity\Post;
use Yeskn\MainBundle\Entity\Tab;

class PostRepository extends EntityRepository
{
    use RepositoryTrait;
    /**
     * @param Tab $tab
     * @param $sort
     * @param $page [$pageNo, $pageSize]
     * @return Post[]
     */
    public function getIndexList($tab, $sort, array $page)
    {
        list($pageNo, $pageSize) = $page;
        list($sort, $order) = $sort;

        $cursor =  $pageSize * ($pageNo - 1);

        $qb = $this->createQueryPostBuilder($tab);

        $total = $qb->select('count(p)')->getQuery()->getSingleScalarResult();

        $qb->select('p')
            ->orderBy('p.isTop', 'desc')
            ->addOrderBy('p.' . $sort,$order)
            ->setFirstResult($cursor)
            ->setMaxResults($pageSize);

        return [$total, $qb->getQuery()->getResult()];
    }

    /**
     * @param Tab|null $tab
     * @return QueryBuilder
     * @throws \InvalidArgumentException
     */
    private function createQueryPostBuilder($tab)
    {
        $em = $this->_em;
        $qb = $this->createQueryBuilder('p')->where('p.isDeleted = false');

        if ($tab) {
            $tabCond = $qb->expr()->orX();
            $tabCond->add($qb->expr()->eq('p.tab', $tab->getId()));

            if ($tab->getLevel() == 1) {
                $subQuery =  $em->createQueryBuilder()
                    ->select('t')
                    ->from('YesknMainBundle:Tab', 't')
                    ->where('t.parent = :parent')
                    ->andWhere('t.level = 2')
                    ->getDQL();
                $tabCond->add($qb->expr()->in('p.tab', $subQuery));
            }

            $qb->andWhere($tabCond)
                ->setParameter('parent', $tab);
        }

        return $qb;
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
