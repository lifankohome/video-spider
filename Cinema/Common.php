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
	    <li><a href='television.php'>电视机</a></li>
	    <li><a href='hotSearch.php'>排行榜</a></li>
	    <li><a href='about.html'>使用说明</a></li>
	    <li style='float: right;background-color: #ddd;border-top-right-radius: 5px;border-bottom-right-radius: 5px'>
	        <input type='text' id='searchBox' style='margin-left: 1.5pc;height: 30px;color: #F40;padding: 0 5px;outline: none' placeholder='$defaultSearch / 粘贴视频地址'>
	        <span id='searchText' style='display: inline-block;padding: 0 1pc;'>影视爬虫</span>
	    </li>
	</ul>";
    }

    public static $QQGroup = "<p style='text-align: center;background: #eee;padding: 6px 2px;border-radius: 2px;'>请使用电脑访问本站获取最佳的使用体验！！（网站使用cookies保存您的观看记录，如果24h内没有访问本网站观看记录会自动删除）</p>";

    public static $footer = "<p style='font-size: 12px;color: #555'>Copyright &copy; <a href='https://hpu.lifanko.cn' style='font-size: 12px;color: #333'>lifanko</a> 2017-2018 豫ICP备16040860号-1</p>";
}