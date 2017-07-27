<?php

namespace Monkey\Generator;

use Monkey\Contracts\Generator;
use Monkey\Support\Arr;

/**
 * 生成随机字符串
 */
class Str implements Generator
{
    public function make($rules = null)
    {
        $length = Arr::get($rules, 'len', mt_rand(1, 50));
        $chars = Arr::get($rules, 'chars', 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890');
        $charslen = strlen($chars);
        if($length > $charslen) {
            $chars = str_repeat($chars, ceil($length/$charslen));
        }
        $randomstr = str_shuffle($chars);
        return substr($randomstr, 0, $length);
    }
}