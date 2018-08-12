<?php
/**
 * This file is part of project Vmoex.
 *
 * Author: Jake
 * Create: 2018-05-27 16:56:39
 */

namespace Yeskn\WebBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TopClickController extends Controller
{
    /**
     * @Route("/topclick", name="top_click")
     */
    public function topClickController()
    {
        return $this->render('@YesknWeb/topclick.html.twig');
    }
}