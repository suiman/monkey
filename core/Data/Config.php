<?php
/**
 * Created by PhpStorm.
 * User: suiman
 * Date: 2017/7/15
 * Time: 下午12:05
 */

namespace Monkey\Data;

use Monkey\File\Reader;
use Monkey\Support\Arr;

class Config
{
    protected $setting;
    protected $api;
    protected $validator;
    protected $middleware;
    protected $repeat;
    protected $duration;
    protected $domain;
    protected $delay;
    protected $timeout;
    protected $worker;
    protected $concurrent;
    protected $reporter;

    public function __construct($setting)
    {
        $setting = $this->setting = $setting;
        $apiconf = Arr::get($setting, 'api');
        $this->api = array();
        if(is_array($apiconf)) {
            foreach($apiconf as $item) {
                $api = Reader::loadConfig($item);
                if($api && is_array($api)) {
                    $this->api = array_merge($this->api, $api);
                }
            }
        }else {
            $this->api = Reader::loadConfig($apiconf);
        }
        $this->validator = Arr::get($setting, 'validator', 'base');
        $this->middleware = Arr::get($setting, 'middleware');
        $this->duration = Arr::get($setting, 'duration');
        $this->repeat = Arr::get($setting, 'repeat');
        $this->domain = Arr::get($setting, 'domain', '');
        $this->delay = Arr::get($setting, 'delay', 0);
        $this->timeout = Arr::get($setting, 'timeout', 10);
        $this->worker = Arr::get($setting, 'worker', 'standard');
        $this->concurrent = Arr::get($setting, 'concurrent', 1);
        $this->reporter = Arr::get($setting, 'reporter', 'standard');
    }

    public function __toString()
    {
        return json_encode($this->setting);
    }

    /**
     * @return null
     */
    public function getConcurrent()
    {
        return $this->concurrent;
    }

    /**
     * @return mixed
     */
    public function getSetting()
    {
        return $this->setting;
    }

    /**
     * @return null
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * @return null
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * @return null
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }

    /**
     * @return null
     */
    public function getRepeat()
    {
        return $this->repeat;
    }

    /**
     * @return null
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @return null
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @return null
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
     * @return null
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @return null
     */
    public function getWorker()
    {
        return $this->worker;
    }

    /**
     * @return null
     */
    public function getReporter()
    {
        return $this->reporter;
    }


}