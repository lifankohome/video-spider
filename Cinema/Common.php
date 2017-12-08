<?php
/**
 * Created by PhpStorm.
 * User: lifanko  lee
 * Date: 2017/12/6
 * Time: 13:12
 */
namespace Cinema;

class Common
{
    public static $header = "
    <ul>
        <li><a href='http://hpu.lifanko.cn'>首页</a></li>
        <li><a href='../index.php'>电影</a></li>
        <li><a href='../teleplay.php'>电视剧</a></li>
        <li><a href='../television.php'>电视机</a></li>
        <li style='float: right;padding: 0 1pc;background-color: #ddd'>影视爬虫</li>
    </ul>";

    public static $footer = "<p style='font-size: 12px;color: #555'>&copy; Copyright lifanko 2017 December PV:<span id='analytics_pv'></span>
			UV:<span id='analytics_uv'></span> Current PV:<span id='analytics_current_pv'></span></p>";
}