<?php
/**
 * Created by PhpStorm.
 * User: Jake
 * Date: 2016/6/22
 * Time: 16:46
 */

namespace Yeskn\CommonBundle\Twig;


use Doctrine\ORM\EntityManager;
use Yeskn\BlogBundle\Entity\User;

class GlobalValue extends \Twig_Extension
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * GlobalValue constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
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

    public function hotUsers()
    {

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

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('hotPosts',array($this,'hotPosts'),array('is_safe' => 'html'),array('needs_environment' => true)),
            new \Twig_SimpleFunction('hotTags',array($this,'hotTags'),array('is_safe' => 'html'),array('needs_environment' => true)),
            new \Twig_SimpleFunction('hotComments',array($this,'hotComments'),array('is_safe' => 'html'),array('needs_environment' => true)),
            new \Twig_SimpleFunction('unReadMessages',array($this,'unReadMessages'),array('is_safe' => 'html'),array('needs_environment' => true))
        );
    }
}