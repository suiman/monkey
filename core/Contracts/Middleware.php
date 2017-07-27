<?php

namespace Monkey\Contracts;

use Monkey\Http\Request;

interface Middleware
{
    /**
     * @param $request Request
     * @return mixed
     */
    public function through(&$request);
}