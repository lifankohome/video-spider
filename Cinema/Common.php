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
        return "<meta name=\"keywords\" content=\"影视爬虫,{$source}百度云,{$source}免费播放,{$source}在线播放,电视直播,电视点播,最新电影,最新大片,最热电视剧,VIP视频解析\">
    <meta name=\"description\" content=\"影视爬虫,{$source}百度云,{$source}免费播放,{$source}在线播放,电视直播,电视点播,最新电影,最新大片,最热电视剧,VIP视频解析\">
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
	    <li><a href='hotsearch.php'>影视推荐</a></li>
	    <li><a href='about.html'>说明</a></li>
	    <li id='searchli'>
	        <input type='text' id='searchBox' placeholder='$defaultSearch | 输入关键词进行搜索'>
	        <span id='searchText'><img src='img/yspc.png' style=''></span>
	    </li>
	</ul>
";
    }

    public static $history = '<div class="history">
    <span onmouseover="showHistory()" onmouseout="hideHistory()" class="btn-history">播放<br>历史</span>
    <iframe onmouseover="showHistory()" onmouseout="hideHistory()" id="fra-history" class="fra-history" src="history.html"></iframe></div>';

    public static $tip = "<p id='tip'>影视爬虫使用cookie技术(包含第三方cookie)来实现网站功能，有关cookie使用的详细信息请点击<a href='about.html#cookie' style='color: #333'>这里</a><span id='sCount'></span></p>";

    public static $footer = "<p style='font-size: 12px;color: #000;margin-top: -5px'>Copyright &copy; <a href='https://hpu.lifanko.cn' style='color: #333'>lifanko</a> 2017-2020 <a href='http://www.miitbeian.gov.cn/' style='color: #333'>豫ICP备16040860号-1</a><span style='float: right;font-weight: bold'>Cookie技术有效期: 24h</span></p>";
}