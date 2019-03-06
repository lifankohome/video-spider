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
        return "<meta name=\"keywords\" content=\"lifanko 影视爬虫 {$source}百度云 {$source}免费播放 {$source}在线播放 电视直播 电视点播 最新电影 最新大片 最热电视剧 VIP视频解析\">
    <meta name=\"description\" content=\"lifanko 影视爬虫 {$source}百度云 {$source}免费播放 {$source}在线播放 电视直播 电视点播 最新电影 最新大片 最热电视剧 VIP视频解析\">
";
    }

    public static function getHeader()
    {
        $defaultSearch = Spider::getHistory(1);
        if ($defaultSearch == '<li><a>数据为空</a></li>') {
            $defaultSearch = '';
        }

        return $header = "<ul>
        <li><a href='https://hpu.lifanko.cn'>首页</a></li>
	    <li><a href='index.php'>电影</a></li>
	    <li><a href='variety.php'>综艺</a></li>
	    <li><a href='teleplay.php'>电视剧</a></li>
	    <li><a href='hotSearch.php'>排行榜</a></li>
	    <li><a href='about.html'>使用说明</a></li>
	    <li><a href='http://ali.lifanko.cn/music/'>橡皮音乐</a></li>
	    <li id='searchli'>
	        <input type='text' id='searchBox' autofocus='autofocus' placeholder='$defaultSearch | 粘贴腾讯/优酷/爱奇艺VIP视频地址'>
	        <span id='searchText' style='display: inline-block;padding: 0 1pc;margin-left: -0.5pc'><img src='img/yspc.png' style='margin: 0;height: 26px;position: relative;top: 7px'></span>
	    </li>
	</ul>";
    }

    public static $QQGroup = "<p id='QQGroup'>影视爬虫使用cookie技术(包含第三方cookie)来实现网站功能，有关cookie使用的详细信息请点击<a href='http://ali.lifanko.cn/video/about.html#cookie' style='color: #333'>这里</a><span id='sCount'></span></p>";

    public static $footer = "<p style='font-size: 12px;color: #000'>Copyright &copy; <a href='https://hpu.lifanko.cn' style='color: #333'>lifanko</a> 2017-2019 <a href='http://www.miitbeian.gov.cn/' style='color: #333'>豫ICP备16040860号-1</a></p>";
}