<?php

namespace Monkey\Contracts;


interface Validator
{
    /**
     * @param $response array
     * @return boolean
     */
    public function verify($response);
}