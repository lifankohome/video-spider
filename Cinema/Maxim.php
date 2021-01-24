<?php

/**
 * Created by PhpStorm.
 * User: lifanko  lee
 * Date: 2017/8/25
 * Time: 12:39
 */

namespace Cinema;

class Maxim
{
    public static function Curl($url, $timeout = 2)
    {
        $con = curl_init($url);

        curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($con, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($con, CURLOPT_TIMEOUT, $timeout);
        $content = curl_exec($con);
        curl_close($con);

        return $content;
    }

    public static function get()
    {
        $url = 'https://hpu.lifanko.cn/maxim';
        return Maxim::Curl($url);
    }
}