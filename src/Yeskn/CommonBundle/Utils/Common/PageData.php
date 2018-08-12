<?php
/**
 * This file is part of project vmoex.
 *
 * Author: Jaggle
 * Create: 2018-08-12 12:39:30
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