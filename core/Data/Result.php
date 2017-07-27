<?php
/**
 * Created by PhpStorm.
 * User: suiman
 * Date: 2017/7/15
 * Time: 下午4:51
 */

namespace Monkey\Data;


use Monkey\Support\Arr;

class Result
{

    protected $request_total;
    protected $success_total;
    protected $failed_total;
    protected $total_time;
    protected $avg_time;
    protected $connect_time;
    protected $avg_connect_time;

    public function __construct($result = null)
    {
        if($result) {
            $this->fromJson($result);
        }else {
            $this->request_total = 0;
            $this->success_total = 0;
            $this->failed_total = 0;
            $this->total_time = 0;
            $this->avg_time = 0;
            $this->connect_time = 0;
            $this->avg_connect_time = 0;
        }
    }

    public function toArray()
    {
        $data = array(
            'request_total' => $this->request_total,
            'success_total' => $this->success_total,
            'failed_total' => $this->failed_total,
            'total_time' => $this->total_time,
            'avg_time' => $this->getAvgTime(),
            'connect_time' => $this->connect_time,
            'avg_connect_time' => $this->getAvgConnectTime(),
        );
        return $data;
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public function fromJson($json)
    {
        $data = json_decode($json, true);
        $this->request_total = Arr::get($data, 'request_total', 0);
        $this->success_total = Arr::get($data, 'success_total', 0);
        $this->failed_total = Arr::get($data, 'failed_total', 0);
        $this->total_time = Arr::get($data, 'total_time', 0);
        $this->avg_time = Arr::get($data, 'avg_time', 0);
        $this->avg_time = Arr::get($data, 'connect_time', 0);
        $this->avg_time = Arr::get($data, 'avg_connect_time', 0);
    }

    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * @return mixed
     */
    public function getRequestTotal()
    {
        return $this->request_total;
    }

    /**
     * @return mixed
     */
    public function getSuccessTotal()
    {
        return $this->success_total;
    }

    /**
     * @return mixed
     */
    public function getFailedTotal()
    {
        return $this->failed_total;
    }

    /**
     * @return mixed
     */
    public function getTotalTime()
    {
        return $this->total_time;
    }

    /**
     * @return mixed
     */
    public function getAvgTime()
    {
        $this->avg_time = $this->request_total > 0 ? ($this->total_time / $this->request_total) : 0;
        return $this->avg_time;
    }

    /**
     * @return int
     */
    public function getConnectTime()
    {
        return $this->connect_time;
    }

    /**
     * @return int
     */
    public function getAvgConnectTime()
    {
        $this->avg_connect_time = $this->request_total > 0 ? ($this->connect_time / $this->request_total) : 0;
        return $this->avg_connect_time;
    }

    public function incRequestTotal($step = 1)
    {
        $this->request_total += $step;
    }

    public function incSuccessTotal($step = 1)
    {
        $this->success_total += $step;
    }

    public function incFailedTotal($step = 1)
    {
        $this->failed_total += $step;
    }

    public function spendTime($long)
    {
        $this->total_time += $long;
    }

    public function spendConnectTime($long)
    {
        $this->connect_time += $long;
    }


}