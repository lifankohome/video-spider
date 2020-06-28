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
    public static function Curl($url)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);

        return curl_exec($ch);
    }

    public static function get()
    {
        $url = 'https://hpu.lifanko.cn/maxim';
        $dom = Maxim::Curl($url);

        return $dom;
    }
}