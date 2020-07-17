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
            if ($inform->end < time()) {
                return false;
            }

            return "<div class='inform'>" . $inform->content . "</div>";
        } else {
            return false;
        }
    }

    public static function visits()
    {
        $filename = 'Cinema/visits.txt';
        if (!is_file($filename)) {
            file_put_contents($filename, '');
        }

        file_put_contents($filename, '*', FILE_APPEND);
        $visits = strlen(file_get_contents($filename));

        $filename_history = 'Cinema/visits_history.json';
        if (!is_file($filename_history)) {
            file_put_contents($filename_history, '{}');
        }
        $visits_history = json_decode(file_get_contents($filename_history), true);
        $date = date('Y-m-d', time() + 3600 * 24 * 2);

        if (!isset($visits_history[$date])) {
            if (count($visits_history) > 0) {
                if (end($visits_history) == '?') {
                    $visits_history[key($visits_history)] = $visits;
                }
            }

            $visits_history[$date] = '?';
            file_put_contents($filename_history, json_encode($visits_history));
            file_put_contents($filename, '');

            $sum = 0;
        } else {
            $sum = $visits;
        }

        $visits_history = array_values($visits_history);
        foreach ($visits_history as $val) {
            if (is_numeric($val)) {
                $sum += $val;
            }
        }

        return '<span style="font-size: 12px;color: #afafaf"> 访客:' . $sum . '</span>';
    }

    public static $ad = "小提示：浏览器输入<span style='color: #F40'>yspc.vip</span>或百度搜索<span style='color: #F40'>“影视爬虫”</span>就可以找到我，聪明人一秒就记住";

    public static $history = '<div class="history">
    <span onmouseover="showHistory()" onmouseout="hideHistory()" class="btn-history">播放<br>历史</span>
    <iframe onmouseover="showHistory()" onmouseout="hideHistory()" id="fra-history" class="fra-history" src="other/history.html"></iframe></div>';

    public static $tip = "<p id='tip'>影视爬虫使用cookie技术(包含第三方cookie)来实现网站功能，有关详细信息请点击<a href='other/about.html#cookie' style='color: #333'>这里</a></p>";

    public static $footer = "<p style='font-size: 12px;color: #000;margin-top: -5px'>Copyright &copy; <a href='https://hpu.lifanko.cn' style='color: #333'>lifanko</a> 2017-2020 <a href='http://www.beian.miit.gov.cn/' style='color: #333'>豫ICP备16040860号-1</a><span style='float: right;font-weight: bold'>Cookie技术有效期: 7d</span></p>";
}