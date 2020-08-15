<?php
/**
 * Created by PhpStorm.
 * User: lifanko  lee
 * Date: 2017/12/6
 * Time: 13:12
 */

namespace Cinema;

use Visits\Visits;

include_once 'Spider.php';
include_once 'Visits/Visits.php';

class Common
{
    public static function SEO($source = '')
    {
        $source = empty($source) ? '海量影视' : '《' . $source . '》';
        return '<meta name="keywords" content="影视爬虫,' . $source . '高清无广告在线观看,电影、电视剧、综艺、动漫免费在线播放,最新电影,最热电视剧,最火综艺,最新动漫">
    <meta name="description" content="影视爬虫为您提供最新最好看的影视内容,高清无广告资源每日更新,' . $source . '免费在线播放,最新电影,最热电视剧,最火综艺,最新动漫">
';
    }

    public static function inform()
    {
        $inform = file_get_contents('Cinema/inform.json');
        if (!empty($inform)) {
            $inform = json_decode($inform);
            if ($inform->start > time() || $inform->end < time()) {
                return false;
            }

            return "<div class='inform'>" . $inform->content . "</div>";
        } else {
            return false;
        }
    }

    public static function visits()
    {
        $Visits = new Visits('Visits/', 'cinema');

        return $Visits->update();
    }

    public static $search_box = "<li id='searchli' style='position: relative'>
            <label for='searchBox'></label>
            <input type='text' id='searchBox' oninput='holder()' placeholder='输入关键词 - 黑科技全网搜索' autocomplete='off'>
            <div id='holder'>
                <ul id='holder_list'></ul>
            </div>
            <span id='searchText'><img src='img/yspc.png' style='' alt='yspc'></span>
        </li>";

    public static $ad = "小提示：浏览器输入<span style='color: #F40'>yspc.vip</span>或百度搜索<span style='color: #F40'>“影视爬虫”</span>就可以找到我，聪明人一秒就记住";

    public static $history = '<div id="history"><span id="history-btn">播放<br>历史</span><iframe id="history-tip" src="other/history.html"></iframe></div>';

    public static $tip = "<p id='tip'>影视爬虫使用cookie技术(包含第三方cookie)来实现网站功能，有关详细信息请点击<a href='other/about.html#cookie' style='color: #333'>这里</a></p>";

    public static $footer = "<p style='font-size: 12px;color: #000;margin-top: -5px'>Copyright &copy; <a href='https://hpu.lifanko.cn' style='color: #333'>lifanko</a> 2017-2020 <a href='http://www.beian.miit.gov.cn/' style='color: #333'>豫ICP备16040860号-1</a><span style='float: right;font-weight: bold'>Cookie技术有效期: 7d</span></p>";
}