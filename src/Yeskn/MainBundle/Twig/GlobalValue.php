<?php

namespace Yeskn\MainBundle\Twig;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Yeskn\MainBundle\Entity\User;
use Twig;

class GlobalValue extends AbstractExtension
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
     * @var Router
     */
    private $router;


    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    private $socketHost;

    /**
     * GlobalValue constructor.
     * @param EntityManagerInterface $em
     * @param TranslatorInterface $translator
     * @param RouterInterface $router
     * @param TokenStorageInterface $tokenStorage
     * @param $socketHost
     */
    public function __construct(
        EntityManagerInterface $em,
        TranslatorInterface $translator,
        RouterInterface $router,
        TokenStorageInterface $tokenStorage,
        $socketHost
    )
    {
        $this->em = $em;
        $this->translator = $translator;
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
        $this->socketHost = $socketHost;
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
            $res = (int)$this->em->getRepository('YesknMainBundle:Sign')
                ->createQueryBuilder('p')
                ->select('COUNT(p)')
                ->where('p.user = :user')->setParameter('user', $user)
                ->andWhere('p.date = :date')->setParameter('date', new \DateTime(date('Y-m-d')))
                ->getQuery()
                ->getSingleScalarResult();

            return (bool)$res;
        } catch (NoResultException $exception) {
            return false;
        } catch (NonUniqueResultException $exception) {
            return true;
        }
    }

    public function ellipsis($string, $length)
    {
        if (mb_strlen($string) > $length) {
            return mb_substr($string, 0, $length) . '...';
        }

        return $string;
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
            return (intval($diff) ?: 1) . $second . $ago;
        } else if ($diff <= 60 * 60) {
            $m = intval($diff / 60);
            $s = intval($diff % 60);
            return $m . $minute . ($s ? $s . $second : '') . $ago;
        } else if ($diff <= 24 * 60 * 60) {
            $h = intval($diff / (60 * 60));
            $m = intval(($diff - $h * (60 * 60)) / 60);
            return $h . $hour . ($m ? $m . $minute : '') . $ago;
        } else {
            $d = intval($diff / (24 * 60 * 60));
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
     */
    public function siteState()
    {
        static $site;

        if (empty($site)) {
            $site = [
                'topicCount' => $this->em->getRepository('YesknMainBundle:Post')->countPost(),
                'userCount' => $this->em->getRepository('YesknMainBundle:User')->countUser(),
                'commentCount' => $this->em->getRepository('YesknMainBundle:Comment')->countComment(),
                'onlineUserCount' => $this->em->getRepository('YesknMainBundle:Active')->countOnlineUser(),
                'footerLinks' => $this->em->getRepository('YesknMainBundle:FooterLink')
                    ->findBy([], ['priority' => 'DESC'])
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

    public function javascriptVariables($postId)
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if ($user instanceof UserInterface) {
            $userParam = [
                'username' => $user->getUsername(),
                'socketToken' => ''
            ];

            $noticeCount = $this->em->getRepository('YesknMainBundle:Notice')->getUnreadCount($user->getId());
            $messageCount = count($this->em->getRepository('YesknMainBundle:Message')->getUnReadMessages($user));

        } else {
            $userParam = [
                'username' => 'guest_' . time(),
                'socketToken' => ''
            ];

            $noticeCount = 0;
            $messageCount = 0;
        }

        $lastChat = $this->em->getRepository('YesknMainBundle:Chat')->findOneBy([], ['id' => 'DESC']);

        if ($lastChat) {
            $lastChatId = $lastChat->getId();
        } else {
            $lastChatId = 0;
        }

        $result = [
            'socketHost' => $this->socketHost,
            'user' => $userParam,
            'links' => [
                'G_info_link' => $this->router->generate('info'),
                'G_set_message_red_link' => $this->router->generate('set_message_red'),
                'G_sign_link' => $this->router->generate('sign'),
                'G_cdn__Path' => '',
                'G_set_locale_link' => $this->router->generate('set_locale'),
                'G_thumb_up__link' => $this->router->generate('thumb_up_comment'),
                'add_comment_to_post' => $this->router->generate('add_comment_to_post', ['postId' => $postId]),
                'send_chat' => $this->router->generate('send_chat'),
                'close_alert' => $this->router->generate('status_close_alert')
            ],
            'trans' => [
                'thumbup' => $this->translator->trans('like'),
                'message' => $this->translator->trans('messages'),
                'action_too_quick' => $this->translator->trans('action_too_fast'),
            ],
            'disableLog' => true,
            'noticeCount' => $noticeCount,
            'messageCount' => $messageCount,
            'lastChatId' => $lastChatId
        ];

        return json_encode($result);
    }

    public function javascriptPlugins()
    {
        return json_encode([
            'WangEditor' => [
                'scripts' => ['/assets/lib/wangeditor/release/wangEditor.min.js'],
                'links' => ['/assets/lib/wangeditor/release/wangEditor.min.css'],
                'identifier' => 'wangEditor' // 插件提供的构造函数
            ],
            'landLord' => [
                'scripts' => ['/assets/lib/live2d/js/live2d.js', '/assets/lib/live2d/js/message.js'],
                'links' => ['/assets/lib/live2d/css/live2d.css'],
                'identifier' => null,
            ],
            'atwho' => [
                'scripts' => [
                    '/assets/lib/Caret.js/dist/jquery.caret.min.js',
                    '/assets/lib/jquery.atwho/dist/js/jquery.atwho.min.js'
                ],
                'links' => ['/assets/lib/jquery.atwho/dist/css/jquery.atwho.min.css'],
                'identifier' => null
            ],
            'laydate' => [
                'scripts' => [
                    '/assets/lib/laydate/dist/laydate.js'
                ],
                'identifier' => 'laydate'
            ],
        ]);
    }

    public function tabsWidget()
    {
        static $tabs = [];

        if (empty($tabs)) {
            $tabs = $this->em->getRepository('YesknMainBundle:Tab')->getTabsForWidget();
        }

        return $tabs;
    }

    public function adsWidget()
    {
        static $ads = [];

        if (empty($ads)) {
            $ads = $this->em->getRepository('YesknMainBundle:Advertisement')
                ->findBy(['enable' => true]);
        }

        return $ads;
    }

    public function getFilters()
    {
        return [
            new Twig\TwigFilter('signed', [$this, 'signed']),
            new Twig\TwigFilter('ago', [$this, 'ago']),
            new Twig\TwigFilter('ellipsis', [$this, 'ellipsis'])
        ];
    }

    public function getFunctions()
    {
        return [
            new Twig\TwigFunction('hotPosts', [$this, 'hotPosts', ['needs_environment' => true, 'is_safe' => 'html']]),
            new Twig\TwigFunction('hotTags', [$this, 'hotTags', ['needs_environment' => true, 'is_safe' => 'html']]),
            new Twig\TwigFunction('hotComments', [$this, 'hotComments', ['needs_environment' => true, 'is_safe' => 'html']]),
            new Twig\TwigFunction('unReadMessages', [$this, 'unReadMessages', ['needs_environment' => true, 'is_safe' => 'html']]),
            new Twig\TwigFunction('hotUsers', [$this, 'hotUsers', ['needs_environment' => true, 'is_safe' => 'html']]),
            new Twig\TwigFunction('onlineUserCount', [$this, 'onlineUserCount', ['needs_environment' => true, 'is_safe' => 'html']]),
            new Twig\TwigFunction('siteState', [$this, 'siteState', ['needs_environment' => true, 'is_safe' => 'html']]),
            new Twig\TwigFunction('javascriptVariables', [$this, 'javascriptVariables']),
            new Twig\TwigFunction('javascriptPlugins', [$this, 'javascriptPlugins']),
            new Twig\TwigFunction('tabsWidget', [$this, 'tabsWidget']),
            new Twig\TwigFunction('adsWidget', [$this, 'adsWidget']),
        ];
    }
}
