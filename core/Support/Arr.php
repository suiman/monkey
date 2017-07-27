<?php
/**
 * Created by PhpStorm.
 * User: suiman
 * Date: 2017/7/15
 * Time: 上午4:01
 */

namespace Monkey\Support;


class Arr
{
    public static function get($array, $key, $default = null)
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }

}