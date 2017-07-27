<?php

namespace Monkey\Validator;

use Monkey\Contracts\Validator;

class Base implements Validator
{
    public function verify($response)
    {
        return $response['succ'];
    }

}