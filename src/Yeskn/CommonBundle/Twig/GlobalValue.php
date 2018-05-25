<?php
/**
 * Created by PhpStorm.
 * User: Jake
 * Date: 2016/6/22
 * Time: 16:46
 */

namespace Yeskn\CommonBundle\Twig;


use Doctrine\ORM\EntityManager;

class GlobalValue extends \Twig_Extension
{
    private $em;

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
            array(),
            array(),
            8
        );
        return $posts;
    }

    public function hotComments()
    {
        $comments = $this->em->getRepository('YesknBlogBundle:Comment')
            ->findBy([], [], 5);

        return $comments;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('hotPosts',array($this,'hotPosts'),array('is_safe' => 'html'),array('needs_environment' => true)),
            new \Twig_SimpleFunction('hotTags',array($this,'hotTags'),array('is_safe' => 'html'),array('needs_environment' => true)),
            new \Twig_SimpleFunction('hotComments',array($this,'hotComments'),array('is_safe' => 'html'),array('needs_environment' => true))
        );
    }

    public function getName()
    {
        return 'app.extension';
    }
}