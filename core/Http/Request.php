<?php

namespace Monkey\Http;

class Request implements \ArrayAccess
{
    protected $url;
    protected $params;
    protected $headers;
    protected $method;

    public function __construct($url = '', $params = array(), $headers = array(), $method = 'GET')
    {
        $this->url = $url;
        $this->params = $params;
        $this->headers = $headers;
        $this->method = $method;
    }

    /**
     * 封装基本HTTP请求
     */
    public function send($timeout = 10)
    {
        $url = $this->url;
        $params = $this->params;
        $headers = $this->headers;
        $method = $this->method;

        $curl = curl_init();

        //超时时间
        $options[CURLOPT_TIMEOUT] = $timeout;
        //返回结果不直接显示
        $options[CURLOPT_RETURNTRANSFER] = true;

        //请求参数
        if($params && is_array($params)) {
            if($method == 'GET') {
                //GET方式(默认)
                $url = rtrim($url, '/') . '?' . http_build_query($params);
            }else {
                //POST方式
                $options[CURLOPT_POSTFIELDS] = $params;
            }
            //地址
            $options[CURLOPT_URL] = $url;
        }
        //请求头
        if($headers && is_array($headers)) {
            $options[CURLOPT_HTTPHEADER] = $headers;
        }

        curl_setopt_array($curl, $options);
        $response = new Response($curl);
        curl_close($curl);
        return $response;
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
        $this->$offset = $value;
    }

    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }

    public function setParam($key, $val)
    {
        $this->params[$key] = $val;
    }

    public function setParams($array)
    {
        if(!is_array($array)) return;
        foreach($array as $key=>$val) {
            $this->params[$key] = $val;
        }
    }

    public function getParam($key)
    {
        return isset($this->params[$key]) ? $this->params[$key] : null;
    }

    public function __toString()
    {
        $arr = array(
            'url' => $this->url,
            'params' => $this->params,
            'headers' => $this->headers,
            'method' => $this->method,
        );
        return json_encode($arr);
    }
}