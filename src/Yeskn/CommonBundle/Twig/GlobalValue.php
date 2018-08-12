<?php
/**
 * This file is part of project vmoex.
 * User: Jake
 * Date: 2016/6/22
 * Time: 16:46
 */

namespace Yeskn\CommonBundle\Twig;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Component\Translation\Translator;
use Yeskn\BlogBundle\Entity\User;

class GlobalValue extends \Twig_Extension
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * GlobalValue constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, $translator)
    {
        $this->em = $em;
        $this->translator = $translator;
    }

    /**
     * 判断用户今天是否签到
     *
     * @param User $user
     * @return int
     */
    public function signed(User $user)
    {
        try {
            $res =  (int) $this->em->getRepository('YesknBlogBundle:Sign')
                ->createQueryBuilder('p')
                ->select('COUNT(p)')
                ->where('p.user = :user')->setParameter('user', $user)
                ->andWhere('p.date = :date')->setParameter('date', new \DateTime(date('Y-m-d')))
                ->getQuery()
                ->getSingleScalarResult();

         return (bool) $res;
        } catch (NoResultException $exception) {
            return false;
        } catch (NonUniqueResultException $exception) {
            return true;
        }
    }

    public function ago(\DateTime $dateTime)
    {
        $current = time();
        $ts = $dateTime->getTimestamp();

        $diff = $current - $ts;

        static $ago, $second, $minute, $hour, $day;

        $ago = $ago ?: $this->translator->trans('ago');
        $second = $second ?: $this->translator->trans('second');
        $minute = $minute ?: $this->translator->trans('minute');
        $hour = $hour ?: $this->translator->trans('hour');
        $day = $day ?: $this->translator->trans('day');

        if ($diff < 60) {
            return (intval($diff) ?: 1).$second.$ago;
        } else if ($diff <= 60*60){
            $m = intval($diff/60);
            $s = intval($diff%60);
            return $m. $minute . ($s ? $s. $second : '') . $ago;
        } else if ($diff <= 24*60*60){
            $h = intval($diff/(60*60));
            $m = intval(($diff - $h*(60*60))/60);
            return $h . $hour . ($m ? $m . $minute : '') . $ago;
        } else {
            $d = intval($diff/(24*60*60));
            return $d . $day . $ago;
        }
    }

    public function hotTags()
    {
        $tags = $this->em->getRepository('YesknBlogBundle:Tag')->findAll();
        return $tags;
    }

    public function hotPosts()
    {
        $posts = $this->em->getRepository('YesknBlogBundle:Post')->findBy(
            [], ['views' => 'DESC'], 8);
        return $posts;
    }

    public function hotComments()
    {
        $comments = $this->em->getRepository('YesknBlogBundle:Comment')
            ->findBy([], ['id' => 'DESC'], 5);

        return $comments;
    }

    /**
     * 显示2日内的活跃用户
     *
     * @return mixed
     */
    public function hotUsers()
    {
        $datetime = new \DateTime('-2 day');
        $actives = $this->em->getRepository('YesknBlogBundle:Active')
            ->createQueryBuilder('p')
            ->select('p.date', 'p.createdAt', 'MAX(p.val) as val')
            ->addSelect('u.username', 'u.avatar', 'u.nickname')
            ->leftJoin('p.user', 'u')
            ->where('p.createdAt >= :yd')->setParameter('yd', $datetime, \Doctrine\DBAL\Types\Type::DATETIME)
            ->orderBy('p.createdAt', 'DESC')
            ->groupBy('p.user')
            ->setMaxResults(8)
            ->getQuery()
            ->getArrayResult();

        array_multisort(array_column($actives, 'val'), SORT_DESC, SORT_REGULAR, $actives);

        return $actives;
    }

    /**
     * @return array
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function siteState()
    {
        static $site;

        if (empty($site)) {
            $site = [
                'startedAt' => new \DateTime('2018-05-25'),
                'topicCount' => $this->em->getRepository('YesknBlogBundle:Post')->countPost(),
                'userCount' => $this->em->getRepository('YesknBlogBundle:User')->countUser(),
                'commentCount' => $this->em->getRepository('YesknBlogBundle:Comment')->countComment(),
                'onlineUserCount' => $this->em->getRepository('YesknBlogBundle:Active')->countOnlineUser()
            ];
        }

        return $site;
    }

    /**
     * @param User $user
     * @return array|\Yeskn\BlogBundle\Entity\Message[]
     */
    public function unReadMessages(User $user)
    {
        return $this->em->getRepository('YesknBlogBundle:Message')
            ->findBy(['receiver' => $user, 'isRead' => false], ['createdAt', 'DESC']);
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('signed', array($this,'signed')),
            new \Twig_SimpleFilter('ago', array($this,'ago')),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('hotPosts',array($this,'hotPosts'),array('is_safe' => 'html'),array('needs_environment' => true)),
            new \Twig_SimpleFunction('hotTags',array($this,'hotTags'),array('is_safe' => 'html'),array('needs_environment' => true)),
            new \Twig_SimpleFunction('hotComments',array($this,'hotComments'),array('is_safe' => 'html'),array('needs_environment' => true)),
            new \Twig_SimpleFunction('unReadMessages',array($this,'unReadMessages'),array('is_safe' => 'html'),array('needs_environment' => true)),
            new \Twig_SimpleFunction('hotUsers',array($this,'hotUsers'),array('is_safe' => 'html'),array('needs_environment' => true)),
            new \Twig_SimpleFunction('onlineUserCount',array($this,'onlineUserCount'),array('is_safe' => 'html'),array('needs_environment' => true)),
            new \Twig_SimpleFunction('siteState',array($this,'siteState'),array('is_safe' => 'html'),array('needs_environment' => true)),
        );
    }
}