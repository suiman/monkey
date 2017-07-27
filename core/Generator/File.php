<?php

namespace Monkey\Generator;

use Monkey\Contracts\Generator;
use Monkey\Support\Arr;

/**
 * 从文件中随机读取
 *
 * Class File
 * @package Monkey\Generator
 */
class File implements Generator
{
    public function make($rules = null)
    {
        $file = Arr::get($rules, 'file');
        if(!file_exists($file)) {
            die("file {$file} no exist\n");
        }
        $arr = file($file);
        return trim($arr[array_rand($arr)]);
    }
}