<?php
/**
 * Created by PhpStorm.
 * User: Jake
 * Date: 2016/6/23
 * Time: 2:10
 */

namespace Yeskn\UserBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class SettingController
 * @Route("/setting")
 * @package Yeskn\UserBundle\Controller
 */
class SettingController extends Controller
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('@YesknUser/Default/setting.html.twig');
    }
}