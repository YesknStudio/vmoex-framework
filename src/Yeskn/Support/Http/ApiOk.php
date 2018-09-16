<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-16 10:50:16
 */

namespace Yeskn\Support\Http;

class ApiOk extends ApiResponse
{
    public function __construct($detail = null, $message = '操作成功')
    {
        parent::__construct(1, $detail, $message);
    }
}