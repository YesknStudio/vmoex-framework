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

namespace Yeskn\CommonBundle\Utils\Common;

class PageData
{
    public function __construct($data, $count)
    {
        $this->data = $data;
        $this->count = $count;
    }
    
    public $data;
    public $count;
}