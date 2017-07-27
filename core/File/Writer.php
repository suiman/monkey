<?php

namespace Monkey\File;

use Monkey\Data\Path;

class Writer
{

    public static function baseDir()
    {
        return Path::staticDir();
    }

    public static function write($filename, $msg, $taskid, $workerid = null)
    {
        $taskdir = self::baseDir() . '/task/' . $taskid;
        if(!is_dir($taskdir)) {
            @mkdir($taskdir, 0777, true);
        }
        $file = is_null($workerid) ? "{$taskdir}/{$filename}" : "{$taskdir}/{$filename}.{$workerid}";
        file_put_contents($file, $msg."\n", FILE_APPEND);
    }

    public static function rmAll($filename, $taskid)
    {
        $taskdir = self::baseDir() . '/task/' . $taskid;
        $files = glob("{$taskdir}/{$filename}*");
        foreach($files as $file) {
            unlink($file);
        }
    }

    /**
     * 合并同类文件
     *
     * @param $filename string 文件标示(除去后缀名)
     * @param $taskid string 任务ID
     * @param bool $clear 是否删除被合并的文件
     */
    public static function merge($filename, $taskid, $clear = true)
    {
        $taskdir = self::baseDir() . '/task/' . $taskid;
        $input_files = glob("{$taskdir}/{$filename}*");
        $output_file = "{$taskdir}/{$filename}";
        $open = fopen($output_file, "w");
        foreach($input_files as $file) {
            fwrite($open, file_get_contents($file));
        }
        fclose($open);
        if($clear) {
            foreach($input_files as $file) {
                unlink($file);
            }
        }
    }


}