<?php

namespace Monkey\Generator;

use Monkey\Contracts\Generator;
use Monkey\Support\Arr;

/**
 * 生成随机数字
 */
class Num implements Generator
{

    public function make($rules = null)
    {
        $min = Arr::get($rules, 'min', -100);
        $max = Arr::get($rules, 'max', 1000);
        ($min > $max) && $this->swap($min, $max);
        if(is_float($min) || is_float($max)) {
            //返回小数
            $float = (mt_rand() / mt_getrandmax()); //生成0-1之间随机小数
            $num = $min + ($float * ($max - $min));
        }else {
            //返回整数
            $num = mt_rand($min, $max);
        }
        return $num;
    }

    /**
     * 交换值
     */
    public function swap(&$a, &$b)
    {
        $tmp = $a;
        $a = $b;
        $b = $tmp;
    }
}