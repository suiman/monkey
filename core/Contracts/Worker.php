<?php
/**
 * Created by PhpStorm.
 * User: suiman
 * Date: 2017/7/15
 * Time: 上午11:38
 */

namespace Monkey\Contracts;

use Monkey\Data\Config;

interface Worker
{
    public function __construct(Config $setting, $taskid = null, $workerid = null);

    public function work();

}