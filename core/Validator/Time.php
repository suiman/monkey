<?php

namespace Monkey\Validator;

use Monkey\Contracts\Validator;

class Time implements Validator
{
    public function verify($response)
    {
        return $response['time'] < 15;
    }

}