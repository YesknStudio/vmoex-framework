<?php

/*
 * This file is part of project yeskn/vmoex.
 *
 * (c) Jaggle <jaggle@yeskn.com>
 *
 * created at 2018-08-12 12:39:30
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yeskn\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Yeskn\CommonBundle\Utils\Common\Paginator;

class BaseController extends Controller
{
    public static $pageLimit = 20;

    /**
     * @param $total
     * @param $pageCount
     * @return Paginator
     */
    public function getPaginator($total, $pageCount = null)
    {
        if (!$pageCount) {
            $pageCount = self::$pageLimit;
        }
        $request   = $this->container->get('request_stack')->getCurrentRequest();
        return new Paginator($request, $total, $pageCount);
    }
}