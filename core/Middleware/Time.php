<?php

namespace Monkey\MiddleWare;

use Monkey\Contracts\Middleware;

/**
 * 添加时间戳
 *
 * Class Time
 * @package Monkey\MiddleWare
 */
class Time implements Middleware
{
    public function through(&$request)
    {
        $request->setParam('time', time());
    }
}