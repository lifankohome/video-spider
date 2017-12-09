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
        <li><a href='../variety.php'>综艺</a></li>
        <li><a href='../teleplay.php'>电视剧</a></li>
        <li><a href='../television.php'>电视机</a></li>
        <li style='float: right;padding: 0 1pc;background-color: #ddd'>影视爬虫</li>
    </ul>";

    public static $footer = "<p style='font-size: 12px;color: #555'>&copy; Copyright lifanko 2017 December PV:<span id='analytics_pv'></span>
			UV:<span id='analytics_uv'></span> Current PV:<span id='analytics_current_pv'></span></p>";

    public static $QQGroup = "<p style='text-align: center;font-size: 12px;background: #eee;padding: 6px 2px;border-radius: 2px;'>
        <a style='color: black'
           href='http://shang.qq.com/wpa/qunwpa?idkey=66ec6b8cd1a3e11d37657fd34a71c5cb050acd618be30778b0e89f90bdd7a86d'
           target='_blank'>点击加入【问题反馈】&【爬虫技术】交流群：548731707</a>
    </p>";
}