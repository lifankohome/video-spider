<?php
/**
 * Created by PhpStorm.
 * User: lifanko  lee
 * Date: 2017/12/6
 * Time: 12:43
 */

namespace Cinema;

class Config
{
    public static $host = 'http://v.woaik.com';//这里填写你网站的域名 如果放到二级目录 这里也要带上二级目录
    public static $title = 'Video Player by lifankohome';//站点标题
    public static $seo = '免费看各大视频网站VIP电影';//站点关键字
    public static $token = '26dbk*/..all/';//加密字串符 请输入8位以上的数字字母特殊符号 为了防止二次调用
}
