<?php

/*
 * This file is part of project yeskn/vmoex.
 *
 * (c) Jaggle <jaggle@yeskn.com>
 *
 * created at 2018-05-27 16:56:39
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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