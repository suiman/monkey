<?php

namespace Monkey;

use Monkey\File\Reader;
use Monkey\File\Writer;
use Monkey\Support\Factory;
use Monkey\Data\Config;
use Monkey\Data\Path;
use Monkey\Worker;

class Monkey
{
    protected $setting;
    protected $taskid;
    protected $st;
    protected $et;

    public function __construct()
    {
        $this->setPath();
        $this->setTimezone();
        $this->setting = new Config(Reader::loadConfig('setting'));
        $this->taskid = date('Y-m-d H:i:s');
    }

    private function setTimezone()
    {
        date_default_timezone_set('Asia/Shanghai');
    }

    private function setPath()
    {
        Path::set(__DIR__);
    }

    public function start()
    {
        $this->before();
        $this->startWorker($this->setting->getConcurrent());
        $reporter = Factory::makeReporter($this->setting->getReporter(), $this->setting, $this->taskid);
        $reporter->report();
        $this->after();
    }

    public function startWorker($concurrent)
    {
        $pool = array();
        for($i=0; $i<$concurrent; $i++) {
            $pool[] = new Thread($this->setting, $this->taskid, $i);
        }
        foreach($pool as $thread) {
            $thread->start();
        }
        foreach($pool as $thread) {
            $thread->join();
        }
    }

    public function before()
    {
        $this->st = time();
    }

    private function after()
    {
        $this->et = time();
        $str = "\n====================real====================\n";
        $str .= sprintf("%-20s %s\n", 'start', date('Y-m-d H:i:s', $this->st));
        $str .= sprintf("%-20s %s\n", 'end', date('Y-m-d H:i:s', $this->et));
        $str .= sprintf("%-20s %ss\n", 'duration', $this->et - $this->st);
        Writer::write('report', $str, $this->taskid);
        echo $str;
    }
}

class Thread extends \Thread
{
    /**
     * @var Config
     */
    protected $setting;
    protected $taskid;
    protected $workerid;

    public function __construct($setting, $taskid, $workerid)
    {
        $this->setting = $setting;
        $this->taskid = $taskid;
        $this->workerid = $workerid;
    }

    public function run()
    {
        //rebuild autoload context
        require 'vendor/autoload.php';

        $worker = Factory::makeWorker($this->setting->getWorker(), $this->setting, $this->taskid, $this->workerid);
        $worker->work();
    }
}

require 'vendor/autoload.php';
$monkey = new Monkey();
$monkey->start();


