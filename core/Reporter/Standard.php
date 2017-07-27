<?php
/**
 * Created by PhpStorm.
 * User: suiman
 * Date: 2017/7/15
 * Time: 上午11:52
 */

namespace Monkey\Reporter;

use Monkey\Contracts\Reporter;
use Monkey\Data\Result;
use Monkey\Data\Path;
use Monkey\File\Writer;

class Standard implements Reporter
{
    protected $setting;
    protected $taskid;

    public function __construct($setting, $taskid = null)
    {
        $this->setting = $setting;
        $this->taskid = $taskid;
    }

    public function report()
    {
        $results = $this->readResults($this->taskid);

        //输出和保存结果
        $str =  "\n====================setting====================\n";
        $str .= sprintf("%-20s %s\n", 'domain', $this->setting->getDomain());
        $str .= sprintf("%-20s %s\n", 'concurrence', $this->setting->getConcurrent());
        $str .= sprintf("%-20s %s\n", 'repeat', $this->setting->getRepeat());
        $str .= sprintf("%-20s %s\n", 'duration', $this->setting->getDuration());
        $str .= sprintf("%-20s %s\n", 'validator', $this->setting->getValidator());

        $str .=  "\n====================report====================\n";
        $str .= sprintf("%-40s %10s %10s %10s %10s %10s\n",'api','all','success','failed','connect_time','avg_time');
        $sum = new Result();
        foreach($results as $url=>$result) {
            $url = str_replace($this->setting->getDomain(), '', $url);
            $str .= sprintf("%-40s %10d %10d %10d %10.2f %10.2f\n",
                $url,
                $result->getRequestTotal(),
                $result->getSuccessTotal(),
                $result->getFailedTotal(),
                $result->getAvgConnectTime(),
                $result->getAvgTime()
            );
            $sum->incRequestTotal($result->getRequestTotal());
            $sum->incSuccessTotal($result->getSuccessTotal());
            $sum->incFailedTotal($result->getFailedTotal());
            $sum->spendTime($result->getTotalTime());
            $sum->spendConnectTime($result->getConnectTime());
        }
        $str .= sprintf("%-40s %10d %10d %10d %10.2f %10.2f\n",
            'sum',
            $sum->getRequestTotal(),
            $sum->getSuccessTotal(),
            $sum->getFailedTotal(),
            $sum->getAvgConnectTime(),
            $sum->getAvgTime()
        );
        echo $str;

        Writer::merge('error', $this->taskid);
        Writer::write('report', $str, $this->taskid);
    }

    /**
     * 整理合并所有的结果文件
     *
     * @param $taskid
     * @return array
     */
    private function readResults($taskid)
    {
        $taskdir = Path::staticDir() . '/task/' . $taskid;
        $files = glob("{$taskdir}/result*");
        $results = array();
        foreach($files as $result_file) {
            $content = unserialize(file_get_contents($result_file));
            foreach($content as $url=>$result) {
                if(!isset($results[$url])) {
                    $results[$url] = new Result();
                }
                if($result instanceof Result) {
                    $results[$url]->incRequestTotal($result->getRequestTotal());
                    $results[$url]->incSuccessTotal($result->getSuccessTotal());
                    $results[$url]->incFailedTotal($result->getFailedTotal());
                    $results[$url]->spendTime($result->getTotalTime());
                    $results[$url]->spendConnectTime($result->getConnectTime());
                }
            }
        }
        Writer::rmAll('result', $this->taskid);
        return $results;
    }

}