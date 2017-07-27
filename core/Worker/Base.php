<?php
/**
 * Created by PhpStorm.
 * User: suiman
 * Date: 2017/7/15
 * Time: 下午4:35
 */

namespace Monkey\Worker;

use Monkey\Contracts\Worker;
use Monkey\Data\Config;
use Monkey\Http\Request;
use Monkey\Http\Response;
use Monkey\Support\Factory;

class Base implements Worker
{
    /**
     * @var \Monkey\Data\Config
     */
    protected $setting;

    protected $taskid;
    protected $workerid;

    public function __construct(Config $setting, $taskid = null, $workerid = null)
    {
        $this->setting = $setting;
        $this->taskid = $taskid;
        $this->workerid = $workerid;
    }

    public function work()
    {
        if($times = $this->setting->getRepeat()) {
            $this->runByLimitedTimes($times);
        }elseif($duration = $this->setting->getDuration()) {
            $this->runWithinDuration($duration);
        }else {
            $this->runOnce();
        }
        $this->whenFinish();
    }

    /**
     * 只跑一次
     */
    private function runOnce()
    {
        $this->runByLimitedTimes(1);
    }

    /**
     * 跑指定次数
     *
     * @param $times
     */
    private function runByLimitedTimes($times)
    {
        for($i=0; $i<$times; $i++) {
            foreach($this->setting->getApi() as $api) {
                $this->tick($api);
            }
        }
    }

    /**
     * 跑一段时间
     *
     * @param $duration
     */
    private function runWithinDuration($duration)
    {
        $st = time();
        while(true) {
            foreach($this->setting->getApi() as $api) {
                $this->tick($api);
                $ct = time();
                if(($ct - $st) > $duration) return;
            }
        }
    }

    /**
     * 执行一次接口请求
     *
     * @param $api
     */
    private function tick($api)
    {
        //延迟执行
        $this->delay($this->setting->getDelay());
        //构造请求体
        $request = Factory::makeRequest($this->setting->getDomain(), $api['url'], $api['params']);
        //请求预处理
        $this->throughMiddleware($this->setting->getMiddleware(), $request);
        //执行请求
        $response = $request->send($this->setting->getTimeout());
        //请求结束
        $this->whenDone($request, $response);
        //记录请求结果
        $this->record($request, $response);
    }

    private function delay($ms)
    {
        if($ms) {
            usleep($ms * 1000);
        }
    }

    private function throughMiddleware($middleware, $request)
    {
        if(empty($middleware) || !is_array($middleware)) return;
        foreach ($middleware as $name) {
            $m = Factory::makeMiddleware($name);
            $m->through($request);
        }
    }

    private function record($request, $response)
    {
        $validator = Factory::makeValidator($this->setting->getValidator());
        $pass = $validator->verify($response);
        if($pass) {
            $this->whenSuccess($request, $response);
        }else {
            $this->whenFailed($request, $response);
        }
    }

    /////////////////
    //hook functions
    /////////////////

    /**
     * 任务结束
     */
    public function whenFinish()
    {

    }

    /**
     * 单次请求结束
     *
     * @param $request Request
     * @param $response Response
     */
    protected function whenDone($request, $response)
    {

    }

    /**
     * 验证不通过
     *
     * @param $request Request
     * @param $response Response
     */
    protected function whenFailed($request, $response)
    {

    }

    /**
     * 验证通过
     *
     * @param $request Request
     * @param $response Response
     */
    protected function whenSuccess($request, $response)
    {

    }

}