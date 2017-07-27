<?php

namespace Monkey\File;

use Monkey\Data\Path;

class Reader
{
    public static function loadConfig($filename)
    {
        $filename .= '.php';
        $file = Path::staticDir() . '/config/' . $filename;
        if(!file_exists($file)) {
            //todo
            die("{$file} no exist");
        }
        return (require $file);
    }
}