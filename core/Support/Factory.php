<?php

namespace Monkey\Support;

use Monkey\Contracts\Generator;
use Monkey\Contracts\Validator;
use Monkey\Contracts\Middleware;
use Monkey\Contracts\Worker;
use Monkey\Http\Request;
use Monkey\Contracts\Reporter;
use Monkey\Data\Config;

/**
 * 工厂类
 */

class Factory
{
    /**
     * 生成指定类型参数
     */
    public static function makeParams($params_conf)
    {
        $params = array();
        foreach($params_conf as $param) {
            $name = $param['name'];
            if(isset($param['val'])) {
                //指定值
                $val = $param['val'];
                $params[$name] = is_array($val) ? $val[array_rand($val)] : $val;
            }else {
                //指定类型(数字,字符串,自定义类型)
                $type = Arr::get($param, 'type', (mt_rand(0, 1) ? 'num' : 'str'));
                $rules = Arr::get($param, 'rules');
                $params[$name] = self::makeGenerator($type)->make($rules);
            }
        }
        return $params;
    }

    /**
     * 验证器
     *
     * @param $name
     * @return Validator
     */
    public static function makeValidator($name)
    {
        $class_name = "Ext\\Validator\\".ucfirst(strtolower($name));
        if(class_exists($class_name)) {
            return (new $class_name);
        }
        $class_name = "Monkey\\Validator\\".ucfirst(strtolower($name));
        if(class_exists($class_name)) {
            return (new $class_name);
        }
        die("validator {$name} no exist");
    }

    /**
     * 中间件
     *
     * @param $name
     * @return Middleware
     */
    public static function makeMiddleware($name)
    {
        $class_name = "Ext\\Middleware\\".ucfirst(strtolower($name));
        if(class_exists($class_name)) {
            return (new $class_name);
        }
        $class_name = "Monkey\\Middleware\\".ucfirst(strtolower($name));
        if(class_exists($class_name)) {
            return (new $class_name);
        }
        die("middleware {$name} no exist\n");
    }

    /**
     * 数据产生器
     *
     * @param $name
     * @return Generator
     */
    public static function makeGenerator($name)
    {
        $class_name = "Ext\\Generator\\".ucfirst(strtolower($name));
        if(class_exists($class_name)) {
            return (new $class_name);
        }
        $class_name = "Monkey\\Generator\\".ucfirst(strtolower($name));
        if(class_exists($class_name)) {
            return (new $class_name);
        }
        die("generator {$class_name} no exist\n");
    }

    /**
     * 请求体
     *
     * @param $domain
     * @param $url
     * @param $params
     * @return Request
     */
    public static function makeRequest($domain, $url, $params)
    {
        //请求地址
        if($domain) {
            $url = rtrim($domain, '/') .'/'. ltrim($url, '/');
        }
        //请求参数
        $params = self::makeParams($params);
        //请求头
        $headers = array();
        //请求方法
        $method = 'GET';
        return (new Request($url, $params, $headers, $method));
    }

    /**
     * 任务执行者
     *
     * @param $name
     * @param $setting Config
     * @param $taskid
     * @param $workerid
     * @return Worker
     */
    public static function makeWorker($name, $setting, $taskid = null, $workerid = null)
    {
        $class_name = "Ext\\Worker\\".ucfirst(strtolower($name));
        if(class_exists($class_name)) {
            return (new $class_name($setting, $taskid, $workerid));
        }
        $class_name = "Monkey\\Worker\\".ucfirst(strtolower($name));
        if(class_exists($class_name)) {
            return (new $class_name($setting, $taskid, $workerid));
        }
        die("worker {$name} no exist\n");
    }

    /**
     * 结果分析器
     *
     * @param $name
     * @return Reporter
     */
    public static function makeReporter($name, $setting, $taskid)
    {
        $class_name = "Ext\\Reporter\\".ucfirst(strtolower($name));
        if(class_exists($class_name)) {
            return (new $class_name($setting, $taskid));
        }
        $class_name = "Monkey\\Reporter\\".ucfirst(strtolower($name));
        if(class_exists($class_name)) {
            return (new $class_name($setting, $taskid));
        }
        die("reporter {$name} no exist\n");
    }

}