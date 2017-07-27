<?php


return array(
    'api' => 'demo', //api配置文件
    'domain' => 'http://127.0.0.1:8080', //请求的基地址
    'validator' => 'json', //验证器
    'repeat' => 100, //[可选]每个api重复请求的次数
    'concurrent' => 30, //[可选]并发请求数
    'duration' => 10, //[可选]测试时间
    'delay' => 0, //[可选]每个请求的延时
    'timeout' => 10, //[可选]请求超时时间
    'middleware' => array('auth', 'count'), //[可选]中间件,用于对请求进行包装,按顺序执行
    'worker' => 'standard', //[可选]任务执行者
    'reporter' => 'standard', //[可选]结果分析器
);