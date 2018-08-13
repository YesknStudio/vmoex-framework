<?php

/*
 * This file is part of project yeskn/vmoex.
 *
 * (c) Jaggle <jaggle@yeskn.com>
 *
 * created at 2018-06-10 12:00:07
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yeskn\WebBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class TrendsController
 * @package Yeskn\WebBundle\Controller
 *
 * @Route("/trends")
 */
class TrendsController extends Controller
{
    /**
     * @Route("", name="trends_index")
     */
    public function indexAction()
    {
        return $this->render('@YesknWeb/trends.html.twig');
    }
}