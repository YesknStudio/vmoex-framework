<?php

namespace Yeskn\MainBundle\Twig;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Translation\Translator;
use Yeskn\MainBundle\Entity\User;

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
            $res =  (int) $this->em->getRepository('YesknMainBundle:Sign')
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
        $tags = $this->em->getRepository('YesknMainBundle:Tag')->findAll();
        return $tags;
    }

    public function hotPosts()
    {
        $posts = $this->em->getRepository('YesknMainBundle:Post')->findBy(
            [], ['views' => 'DESC'], 8);
        return $posts;
    }

    public function hotComments()
    {
        $comments = $this->em->getRepository('YesknMainBundle:Comment')
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
        $actives = $this->em->getRepository('YesknMainBundle:Active')
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
                'topicCount' => 0,
                'userCount' => 0,
                'commentCount' => 0,
                'onlineUserCount' => 0
            ];
        }

        return $site;
    }

    public function avatar(array $user)
    {
        if (empty($user['avatar']) && !empty($user['username'])) {
            $identicon = new \Identicon\Identicon();
            return $identicon->getImageDataUri($user['username']);
        }
        return $user['avatar'];
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
            new \Twig_SimpleFunction('hotPosts',array($this,'hotPosts'),array('needs_environment' => true, 'is_safe' => 'html')),
            new \Twig_SimpleFunction('hotTags',array($this,'hotTags'),array('needs_environment' => true, 'is_safe' => 'html')),
            new \Twig_SimpleFunction('hotComments',array($this,'hotComments'),array('needs_environment' => true, 'is_safe' => 'html')),
            new \Twig_SimpleFunction('unReadMessages',array($this,'unReadMessages'),array('needs_environment' => true, 'is_safe' => 'html')),
            new \Twig_SimpleFunction('hotUsers',array($this,'hotUsers'),array('needs_environment' => true, 'is_safe' => 'html')),
            new \Twig_SimpleFunction('onlineUserCount',array($this,'onlineUserCount'),array('needs_environment' => true, 'is_safe' => 'html')),
            new \Twig_SimpleFunction('siteState',array($this,'siteState'),array('needs_environment' => true, 'is_safe' => 'html')),
            new \Twig_SimpleFunction('avatar',array($this,'avatar'), array('needs_environment' => false)),
        );
    }
}