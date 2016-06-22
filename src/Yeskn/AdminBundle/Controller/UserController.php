<?php
/**
 * Created by PhpStorm.
 * User: Jake
 * Date: 2016/6/22
 * Time: 21:36
 */

namespace Yeskn\AdminBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class UserController
 * @Route("/admin/user")
 * @package Yeskn\AdminBundle\Controller
 */
class UserController extends Controller
{
    /**
     * @Route("/index")
     */
    public function indexAction()
    {
        $users = $this->getDoctrine()->getRepository('YesknBlogBundle:User')->findAll();
        return $this->render('YesknAdminBundle:User:index.html.twig',array(
            'users' => $users
        ));
    }

    /**
     * @Route("/add")
     */
    public function addAction()
    {
        return $this->render('@YesknAdmin/User/add.html.twig');
    }
}