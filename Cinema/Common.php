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
    private static $img_copyright = '';

    public static function SEO($video = false)
    {
        $hour = date('G', time());
        $css_font = ($hour >= 6 && $hour < 22) ? '
    <link type="text/css" rel="stylesheet" href="https://cdn.lifanko.cn/fonts/sourceHanSansCN.css">' : '';

        if ($video == false) {
            $source = empty($source) ? '海量影视' : '《' . $source . '》';
            return '<meta name="keywords" content="影视爬虫官网,' . $source . '高清无广告在线观看,电影、电视剧、综艺、动漫免费在线播放,最新电影,最热电视剧,最火综艺,最新动漫">
    <meta name="description" content="影视爬虫官网,影视爬虫为您提供最新最好看的影视内容,高清无广告资源每日更新,' . $source . '免费在线播放,最新电影,最热电视剧,最火综艺,最新动漫">' . $css_font . '
';
        } else {
            $keywords = $video . '免费在线播放,' . $video . '在线播放,' . $video . '未删减版,' . $video . '下载,' . $video . '百度云';
            return '<meta name="keywords" content="' . $keywords . '">' . $css_font . '
';
        }
    }

    public static function inform()
    {
        $inform = file_get_contents('Cinema/inform.json');
        if (!empty($inform)) {
            $inform = json_decode($inform);
            if ($inform->start > time() || $inform->end < time()) {
                return false;
            }

            return "<div style='text-align: center'><div class='inform'>" . $inform->content . "</div></div>";
        } else {
            return false;
        }
    }

    public static function visits()
    {
        $Visits = new Visits('Visits/', 'cinema');

        return $Visits->update();
    }

    public static $menu = "<li><a href='hot.php'>首页</a></li>
        <li><a href='index.php'>电影</a></li>
        <li><a href='variety.php'>综艺</a></li>
        <li><a href='teleplay.php'>电视剧</a></li>
        <li><a href='anime.php'>动漫</a></li>
        <li><a href='other/about.html' target='_blank'>说明</a></li>
        <li><a href='https://www.lifanko.cn/other/yspc.php' class='donate' target='_blank'>💕打赏💕</a></li>";

    public static $search_box = "<li id='searchli' style='position: relative'>
            <label for='searchBox'></label>
            <input type='text' id='searchBox' oninput='holder()' placeholder='输入关键词 - 黑科技全网搜索' autocomplete='off'>
            <div id='holder'>
                <ul id='holder_list'></ul>
            </div>
            <span id='searchText'><img src='img/yspc.png' style='' alt='yspc'></span>
        </li>";

    public static $ad = "小提示：浏览器输入<span style='color: #44e5f1'>yspc.vip</span>或百度搜索<span style='color: #44e5f1'>“影视爬虫”</span>就可以找到我，聪明人一秒就记住";

    public static $history = "<div id='feedback_btn'><span class='float_btn' style='background-color: rgba(0, 0, 0, 0.7);border: 1px solid #999;'>问题<br>反馈</span></div><div id='history'><span class='float_btn'>播放<br>历史</span><iframe id='history-tip' src='other/history.html'></iframe></div>";

    public static $feedback = "<div id='f-box'><div class='f-pos'><iframe src='other/feedback.html' class='feedback'></iframe></div></div>";

    public static $tip = "<p id='tip'>影视爬虫使用cookie技术(包含第三方cookie)来实现网站功能，有关详细信息请点击<a href='other/about.html#cookie' style='color: #333'>这里</a></p>";

    public static $bounce = '<div class="bounce"><span>想要观看的影视不在列表中？</span><span class="letter">在</span><span class="letter">页</span><span class="letter">面</span><span class="letter">右</span><span class="letter">上</span><span class="letter">角</span><span class="letter">搜</span><span class="letter">索</span><span class="letter">试</span><span class="letter">试</span></div>';

    public static $footer = "<p style='font-size: 12px;color: #000;margin-top: -5px'>Copyright &copy; <a href='https://hpu.lifanko.cn' style='color: #333'>lifanko</a> 2017-2021 <a href='http://www.beian.miit.gov.cn/' style='color: #333'>豫ICP备16040860号-1</a><span style='float: right;font-weight: bold'>Cookie技术有效期: 7d</span></p>";

    public static $pined = "";

    public static function ctl()
    {
        return [
            'code' => -1,
            'content' => "<div class='ctl'>
    <div class='logo tiktok'><img src='img/logo.png' alt='logo'></div>
    <div>系统升级中，服务已暂停；预计恢复时间：2021-12-04 22:00</div>
    <div style='margin-top: 20px;'>站长最新作品：<a href='https://lifanko.cn/eraser/' style='color: #03a9f4;background-color: rgba(255,255,255,0.9);padding: 10px 20px;border-radius: 5px;'>橡皮音乐</a>（超全音乐库，免费试听，免费下载）</div>
</div>",
            'data' => []
        ];
    }

    public static function background()
    {
        $bing_img = file_get_contents('http://cn.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1');
        $res = json_decode($bing_img, true);
        if (isset($res['images'])) {
            $url = 'http://cn.bing.com/' . $res['images'][0]['url'];
            self::$img_copyright = $res['images'][0]['copyright'];

            $style = "background-image: url('$url');";

            return "<div class=\"background-image\" style=\"$style\"></div>";
        } else {
            return '';
        }
    }
}