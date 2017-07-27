<?php
/**
 * Created by PhpStorm.
 * User: suiman
 * Date: 2017/7/15
 * Time: 上午11:36
 */

namespace Monkey\Worker;

use Monkey\File\Writer;
use Monkey\Data\Result;

class Standard extends Base
{
    protected $results;
    protected $errors = array();

    protected function whenSuccess($request, $response)
    {
        $result = $this->getResult($request['url']);
        $result->incSuccessTotal();
    }

    protected function whenFailed($request, $response)
    {
        $result = $this->getResult($request['url']);
        $result->incFailedTotal();
        $this->errors[] = $response->getData();
    }

    protected function whenDone($request, $response)
    {
        $result = $this->getResult($request['url']);
        $result->incRequestTotal();
        $result->spendTime($response['total_time']);
        $result->spendConnectTime($response['connect_time']);
    }

    public function whenFinish()
    {
        Writer::write('result', serialize($this->results), $this->taskid, $this->workerid);
        Writer::write('error', implode("\n", $this->errors), $this->taskid, $this->workerid);
    }

    /**
     * 获取单个url的结果对象
     *
     * @param $url
     * @return Result
     */
    private function getResult($url)
    {
        if(!isset($this->results[$url])) {
            $this->results[$url] = new Result();
        }
        return $this->results[$url];
    }

}