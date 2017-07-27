<?php

namespace Monkey\Validator;

use Monkey\Contracts\Validator;

class Json implements Validator
{
    public function verify($response)
    {
        return $this->is_json($response['data']);
    }

    function is_json($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

}