<?php
/**
 * Created by PhpStorm.
 * User: lifanko  lee
 * Date: 2017/12/6
 * Time: 13:12
 */
namespace Cinema;

class Header
{
    public static $header = "
    <ul>
        <li><a href='#'>首页</a></li>
        <li><a href='movie.php?m=http://www.360kan.com/dianying/list.php?cat=all%26pageno=1'>电影</a></li>
        <li><a href='tv.php/decode/tv.php?u=http://www.360kan.com/dianshi/list.php?cat=all%26pageno=1'>电视剧</a></li>
        <li><a href='zongyi.php/decode/zongyi.php?m=http://www.360kan.com/zongyi/list.php?cat=all%26pageno=1'>综艺</a></li>
        <li><a href='dongman.php?m=http://www.360kan.com/dongman/list.php?cat=all%26pageno=1'>动漫</a></li>
        <li><a href='../../zhibo.php'>电视</a></li>
        <li style='float: right'>影视爬虫</li>
    </ul>";
}