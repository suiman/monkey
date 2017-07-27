<?php


return array(
    array(
        'url' => 'http://127.0.0.1:8080', //接口地址
        'params' => array(
            /**
             * 随机类型
             */
            array(
                'name' => 'any',
            ),

            /**
             * 指定值
             */
            array(
                'name' => 'fixed', //参数名称
                'val' => '123', //固定值，array(1，2，3)时表从1，2，3表示中随机选择一个
            ),

            /**
             * 数字类型
             */
            array(
                'name' => 'number', //参数名称
                'type' => 'num', //数字类型
                'rules' => array(
                    'range' => array('min'=>0, 'max'=>100), //范围[可选]
                ),
            ),

            /**
             * 字符串类型
             */
            array(
                'name' => 'string', //参数名称
                'type' => 'str', //字符串类型
                'rules' => array(
                    'char' => 'abcdefg', //候选字符[可选]
                    'len' => 10, //字符串长度[可选]
                )
            ),

            /**
             * 自定义类型(使用数据生成器,参考Generator/Uuid)
             */
            array(
                'name' => 'custom',
                'type' => 'uuid', //生成器名称
                'rules' => array(),
            ),

        ),
    )
);