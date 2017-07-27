<?php
/**
 * Created by PhpStorm.
 * User: suiman
 * Date: 2017/7/15
 * Time: 上午2:48
 */

namespace Monkey\Http;


class Response implements \ArrayAccess
{
    //返回数据
    protected $data;
    //返回成功,false表示失败
    protected $succ;
    //返回错误信息
    protected $error;
    //返回HTTP状态码
    protected $http_code;
    //请求总时间,单位ms,保留2位小数
    protected $total_time;
    //返回数据大小,单位byte
    protected $size_download;
    //请求数据大小,单位byte
    protected $request_size;
    //DNS时间
    protected $dns_time;
    //连接时间
    protected $connect_time;

    public function __construct($curl)
    {
        $this->data = curl_exec($curl);
        $this->succ = (curl_errno($curl) == 0);
        $this->error = curl_error($curl);
        $this->http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $this->total_time = round(curl_getinfo($curl, CURLINFO_TOTAL_TIME)*1000, 2);
        $this->size_download = curl_getinfo($curl, CURLINFO_SIZE_DOWNLOAD);
        $this->request_size = curl_getinfo($curl, CURLINFO_REQUEST_SIZE);
        $this->dns_time = curl_getinfo($curl, CURLINFO_NAMELOOKUP_TIME);
        $this->connect_time = round(curl_getinfo($curl, CURLINFO_PRETRANSFER_TIME)*1000, 2);
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getSucc()
    {
        return $this->succ;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return mixed
     */
    public function getHttpCode()
    {
        return $this->http_code;
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
    public function getSizeDownload()
    {
        return $this->size_download;
    }

    /**
     * @return mixed
     */
    public function getRequestSize()
    {
        return $this->request_size;
    }

    /**
     * @return mixed
     */
    public function getDnsTime()
    {
        return $this->dns_time;
    }

    /**
     * @return mixed
     */
    public function getConnectTime()
    {
        return $this->connect_time;
    }

    public function __toString()
    {
        $response['data'] = $this->data;
        $response['succ'] = $this->succ;
        $response['error'] = $this->error;
        $response['http_code'] = $this->http_code;
        $response['total_time'] = $this->total_time;
        $response['size_download'] = $this->size_download;
        $response['request_size'] = $this->request_size;
        return json_encode($response);
    }

    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }


}