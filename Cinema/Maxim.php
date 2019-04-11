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
    private static function Curl($url)
    {
        $ch = curl_init($url);//初始化会话

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);//将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
        $result = curl_exec($ch);//抓取的结果

        return $result;
    }

    public static function get()
    {
        $url = 'http://tool.lu/markdown/';
        $dom = Maxim::Curl($url);
        $start = strpos($dom, "<div class=\"note-container\">");
        $end = strpos($dom, "<div class=\"note-bottom\">");
        $res = substr($dom, $start, $end - $start);

        return mb_substr($res, 41, -23);
    }
}