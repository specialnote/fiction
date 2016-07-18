<?php

namespace frontend\models;

class Http
{
    /**
     * @param $url
     *
     * @return bool
     */
    public static function testUrl($url)
    {
        $res = get_headers($url);
        if (false === strpos($res[0], '200 OK')) {
            return false;
        } else {
            return true;
        }
    }
}
