<?php
/**
 * Created by PhpStorm.
 * User: suiman
 * Date: 17/7/19
 * Time: 下午5:41
 */

namespace Monkey\Data;


class Path
{
    protected static $core;
    protected static $static;
    protected static $ext;

    public static function set($base_dir)
    {
        self::$core = "{$base_dir}/core";
        self::$static = "{$base_dir}/static";
        self::$ext = "{$base_dir}/ext";
    }

    public static function coreDir()
    {
        return self::$core;
    }

    public static function staticDir()
    {
        return self::$static;
    }

    public static function extDir()
    {
        return self::$ext;
    }

}