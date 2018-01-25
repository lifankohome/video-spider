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
    public static function getHeader(){
        $defaultSearch = Spider::searchHistory(1);

        return $header = "<ul>
            <li><a href='http://hpu.lifanko.cn'>首页</a></li>
	        <li><a href='http://ali.lifanko.cn/video/index.php'>电影</a></li>
	        <li><a href='http://ali.lifanko.cn/video/variety.php'>综艺</a></li>
	        <li><a href='http://ali.lifanko.cn/video/teleplay.php'>电视剧</a></li>
	        <li><a href='http://ali.lifanko.cn/video/television.php'>电视机</a></li>
	        <li><a href='http://ali.lifanko.cn/video/hotSearch.php'>搜索排行榜</a></li>
	        <li style='float: right;background-color: #ddd'><input type='text' id='searchBox' style='margin-left: 1pc;height: 30px;color: #F40;padding: 0 5px;outline: none' placeholder='$defaultSearch'><span id='searchText' style='display: inline-block;padding: 0 1pc;'>影视爬虫</span></li>
	    </ul>";
    }

    public static $footer = "<p style='font-size: 12px;color: #555'>&copy; Copyright lifanko 2017 December</p>";

    public static $QQGroup = "<p style='text-align: center;font-size: 12px;background: #eee;padding: 6px 2px;border-radius: 2px;'>
        <a style='color: black'
           href='http://shang.qq.com/wpa/qunwpa?idkey=7d555df15dae8e5f29839bc474b172953f5ac15f3018f7e4607454dae583bd9d'
           target='_blank'>点击加入【意见建议】&【问题反馈】&【爬虫技术】交流群：567051081</a>
    </p>";
}