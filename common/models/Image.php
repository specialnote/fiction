<?php

namespace common\models;

class Image
{
    //下载指定文件
    public static function download($url, $pic_name)
    {
        set_time_limit(5 * 60); //限制最大的执行时间
        $path = dirname(__DIR__) . '/../frontend/web/downloads/';
        $urlPath = '/downloads/';
        $newfname = $path . $pic_name;//文件PATH
        $file = fopen($url, 'rb');
        if ($file) {
            $newf = fopen($newfname, 'wb');
            if ($newf) {
                while (!feof($file)) {
                    fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
                }
            }
            if ($file) {
                fclose($file);
            }
            if ($newf) {
                fclose($newf);
            }
        }
        if (!file_exists($newfname)) {
            $newfname = '/images/default.svg';
        } else {
            $newfname = $urlPath.$pic_name;
        }
        return $newfname;
    }
}
