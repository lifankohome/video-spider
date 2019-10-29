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
        return "<meta name=\"keywords\" content=\"影视爬虫 {$source}百度云 {$source}免费播放 {$source}在线播放 电视直播 电视点播 最新电影 最新大片 最热电视剧 VIP视频解析\">
    <meta name=\"description\" content=\"影视爬虫 {$source}百度云 {$source}免费播放 {$source}在线播放 电视直播 电视点播 最新电影 最新大片 最热电视剧 VIP视频解析\">
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
	    <li id='searchli'>
	        <input type='text' id='searchBox' autofocus='autofocus' placeholder='$defaultSearch | 粘贴腾讯/优酷/爱奇艺VIP视频地址'>
	        <span id='searchText' style='display: inline-block;padding: 0 1pc;margin-left: -0.5pc'><img src='img/yspc.png' style='margin: 0;height: 34px;position: relative;top: 11px'></span>
	    </li>
	</ul>
";
    }

    public static $history = '<div class="history" style="position: fixed;right: 2pc;top: 40%;">
    <span onmouseover="showHistory()" onmouseout="hideHistory()" class="btn-history"
          style="background-color: rgba(221,221,221,0.7);padding: 7px 12px;display: inline-block;cursor: pointer;box-shadow: 1px 1px 5px 0 #777;">播放<br>历史</span>
    <iframe onmouseover="showHistory()" onmouseout="hideHistory()" id="fra-history" class="fra-history"
            src="history.html" style="position: absolute;border: none;box-shadow: 1px 1px 5px 0 #777;right: -300px;height: 300px;background-color: #ddd;width: 245px;transition: all .3s 0s;padding: 0;border-radius: 0"></iframe>
            </div>';

    public static $QQGroup = "<p id='QQGroup'>影视爬虫使用cookie技术(包含第三方cookie)来实现网站功能，有关cookie使用的详细信息请点击<a href='about.html#cookie' style='color: #333'>这里</a><span id='sCount'></span></p>";

    public static $footer = "<p style='font-size: 12px;color: #000'>Copyright &copy; <a href='https://hpu.lifanko.cn' style='color: #333'>lifanko</a> 2017-2019 <a href='http://www.miitbeian.gov.cn/' style='color: #333'>豫ICP备16040860号-1</a></p>";
}