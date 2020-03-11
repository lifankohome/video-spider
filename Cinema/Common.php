<?php
/**
 * Created by PhpStorm.
 * User: lifanko  lee
 * Date: 2017/12/6
 * Time: 13:12
 */

namespace Cinema;

include_once('Spider.php');

class Common
{
    public static function SEO($source = '')
    {
        return '<meta name="keywords" content="影视爬虫,' . $source . '百度云,' . $source . '免费播放,' . $source . '在线播放,电视直播,电视点播,最新电影,最新大片,最热电视剧,VIP视频解析">
    <meta name="description" content="影视爬虫,' . $source . '百度云,' . $source . '免费播放,' . $source . '在线播放,电视直播,电视点播,最新电影,最新大片,最热电视剧,VIP视频解析">
';
    }

    public static $ad = "<p style='text-align: center;font-size: 20px;background-color: #333;padding: 10px 5px;color: #FFF;border-radius: 5px'>影视爬虫小提示：浏览器输入<span style='font-size:30px;color: #F40'>yspc.vip</span>就可以找到我，聪明人一秒就记住了</p>";

    public static $history = '<div class="history">
    <span onmouseover="showHistory()" onmouseout="hideHistory()" class="btn-history">播放<br>历史</span>
    <iframe onmouseover="showHistory()" onmouseout="hideHistory()" id="fra-history" class="fra-history" src="other/history.html"></iframe></div>';

    public static $tip = "<p id='tip'>影视爬虫使用cookie技术(包含第三方cookie)来实现网站功能，有关cookie使用的详细信息请点击<a href='other/about.html#cookie' style='color: #333'>这里</a></p>";

    public static $footer = "<p style='font-size: 12px;color: #000;margin-top: -5px'>Copyright &copy; <a href='https://hpu.lifanko.cn' style='color: #333'>lifanko</a> 2017-2020 <a href='http://www.beian.miit.gov.cn/' style='color: #333'>豫ICP备16040860号-1</a><span style='float: right;font-weight: bold'>Cookie技术有效期: 24h</span></p>";
}