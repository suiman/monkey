<?php

namespace Monkey\Validator;

use Monkey\Contracts\Validator;

class Httpcode implements Validator
{
    public function verify($response)
    {
        return $response['http_code'] == 200;
    }

}