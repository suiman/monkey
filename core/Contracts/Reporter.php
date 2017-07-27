<?php
/**
 * Created by PhpStorm.
 * User: suiman
 * Date: 2017/7/15
 * Time: 上午11:51
 */

namespace Monkey\Contracts;

use Monkey\Data\Config;

interface Reporter
{
    /**
     * Reporter constructor.
     * @param $setting Config
     * @param null $taskid
     */
    public function __construct($setting, $taskid = null);

    public function report();

}