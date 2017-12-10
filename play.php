<?php
/**
 * Created by PhpStorm.
 * User: lifanko  lee
 * Date: 2017/12/6
 * Time: 12:37
 */
use Cinema\Common;
use Cinema\Spider;

/**
 * 类自动加载
 * @param $class
 */
function __autoload($class)
{
    $file = $class . '.php';
    if (is_file($file)) {
        /** @noinspection PhpIncludeInspection */
        require_once($file);
    }
}

$player = base64_decode($_GET['play']);

$dom = file_get_contents($player);

$nameDom = '#<h1>(.*?)</h1>#';
$introDom = '#<p class="item-desc js-open-wrap">(.*?)</p>#';
$linkDom = '#<a data-daochu(.*?) href="(.*?)" class="js-site-btn btn btn-play"></a>#';

preg_match_all($nameDom, $dom, $name);
preg_match_all($introDom, $dom, $intro);
preg_match_all($linkDom, $dom, $link);

if (empty($intro[1][0])) {
    $intro = "无";
} else {
    $intro = '　　' . mb_substr($intro[1][0], 9, -14);
}

$name = $name[1][0];

$sets = array();
if (empty($link[2][0])) {
    $multiSets = true;

    $setsDivDom = '/<div class="num-tab-main g-clear js-tab"( style="display:none;")?>[\s\S]+<a data-num="(.*?)" data-daochu="(.*?)" href=(.*?)>/';

    preg_match_all($setsDivDom, $dom, $setsDiv);
    if (empty($setsDiv[0])) {
        $varietyEpisode = true;

        $setsDivDom = '/style="display:block;">[\s\S]+<li  title=\'(.*?)\' class=\'w-newfigure w-newfigure-180x153\'>(.*?)<a href=\'(.*?)\'>/';
        preg_match_all($setsDivDom, $dom, $setsDiv);
        $setsLiDom = '/<li  title=\'(.*?)\' class=\'w-newfigure w-newfigure-180x153\'>(.*?)<a href=\'(.*?)\'>/';
        preg_match_all($setsLiDom, $setsDiv[0][0], $sets);

        $sets[3] = array_unique($sets[3]);  //确保不会有重复剧集
        $sets[1] = array_unique($sets[1]);
    } else {
        $varietyEpisode = false;
        $setsDom = '#<a data-num="(.*?)" data-daochu="to=(.*?)" href="(.*?)">#';
        preg_match_all($setsDom, implode("", $setsDiv[0]), $sets);
    }
} else {
    $multiSets = false;
    $link = $link[2][0];
}

?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $name ?> - 影视爬虫</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <style>
        body {
            width: 80%;
            min-width: 960px;
            margin: 0 auto;
            font-family: "Microsoft JhengHei UI"
        }

        .container ul {
            list-style: none;
        }

        .container ul li {
            float: left;
        }

        .container ul li a {
            font-size: 12px;
            color: black;
            background-color: #eee;
            padding: 5px 10px;
            margin: 3px 5px;
            display: inline-block;
        }

        #cookie {
            background-color: #FCC;
        }

        .player {
            width: 80%;
            margin: 1pc auto;
        }

        iframe {
            width: 100%;
            border: none;
            background-color: #eee;
            padding: 1pc;
            border-radius: 5px;
            margin-left: -1pc;
        }

        button {
            cursor: pointer;
        }
    </style>
    <link type="text/css" rel="stylesheet" href="css/header.css">
    <script type='text/javascript' src='js/jquery-2.1.1.min.js'></script>
</head>
<body>
<header>
    <img src="img/logo.png">
    <?php echo Common::$header ?>
</header>
<div class="container">
    <h3><?php
        if ($multiSets) {
            echo '《' . $name . '》—— 总' . count($sets[3]) . '集<ul>';
            foreach ($sets[3] as $key => $val) {
                if ($varietyEpisode) {
                    echo "<li><a class='videoA' href='$val' target='ajax'>{$sets[1][$key]}</a></li>";
                } else {
                    $key++;
                    echo "<li><a class='videoA' href='$val' target='ajax'>第{$key}集</a></li>";   //集数从1开始
                }
            }
            echo '</ul><div style="clear: both;border-bottom: 1px #ddd solid;padding-top: 1pc"></div>';
        } else {
            echo $name . "——<a class='videoA' href='$link' target='ajax'>立即播放</a>";
        }
        ?></h3>
    <div class="player">
        <iframe onload="iFrameLoad()" id="video" src="loading.html"></iframe>
        <a style="display: none" id="videoLink" href=""></a>
        <script type="text/javascript">
            var videoFrame = document.getElementById('video');  //全局使用
            var videoLink = document.getElementById('videoLink');
            function iFrameLoad() {
                videoFrame.height = videoFrame.contentWindow.document.body.scrollHeight;
            }
        </script>
    </div>
    <?php echo Spider::$parser ?>
    <script type="text/javascript">
        var videoA = $(".videoA");
        var videoLinkBuffer = [];
        var iBuffer = 0;
        for (var i = 0; i < videoA.length; i++) {
            videoLinkBuffer.push(videoA[i].href);
            videoA[i].href = 'javascript:void(0)';
            videoA.eq(i).attr('onclick', 'playUrl(\'' + videoLinkBuffer[i] + '\',\'' + i + '\')');

            if (i > 0 && videoLinkBuffer[i] == getCookie('<?php echo $player; ?>')) {   //非第一集时提供观看进度提示
                videoA[i].setAttribute("id", "cookie");
                iBuffer = i;
            }
        }
        function vParser(url) {
            videoFrame.src = url + videoLink.href;
        }

        function playUrl(sourceUrl, i) {
            setCookie('<?php echo $player; ?>', sourceUrl, 1); //保存当前播放源链接，键为爬取地址，时间为1d=24h

            if (iBuffer > 0) {  //点击不同剧集后立即改变颜色
                videoA[iBuffer].setAttribute("id", "");
            }
            if (iBuffer != i) {
                //iBuffer = i;    //注释本句可以在不跳转的情况下显示已点击的链接，不注释仅显示当前播放剧集
                videoA[i].setAttribute("id", "cookie");
            }

            videoLink.href = sourceUrl;
            vParser('http://aikan-tv.com/?url=');    //默认使用解析器五解析
        }

        function setCookie(cookieKey, cookieValue, expireDays) {
            var expDate = new Date();
            expDate.setDate(expDate.getDate() + expireDays);
            document.cookie = cookieKey + "=" + escape(cookieValue) +
                ((expireDays == null) ? "" : "; expires=" + expDate.toGMTString());
        }

        function getCookie(cookieKey) {
            var arr, reg = new RegExp("(^| )" + cookieKey + "=([^;]*)(;|$)");
            return (arr = document.cookie.match(reg)) ? unescape(arr[2]) : null;
        }
    </script>
    <h3 style="margin-bottom: 5px">剧情简介：</h3>
    <p style="margin-top: 0"><?php echo $intro; ?></p>
</div>
<footer>
    <?php
    echo Common::$QQGroup;
    echo Common::$footer;
    ?>
    <p style="font-size: 12px;text-align: right;margin-top: -25px">Cookie技术有效期:24h</p>
</footer>
<script async src="https://cdn.jsdelivr.net/gh/someartisans/analytics@0.1.0/dist/counter.min.js"></script>
</body>
</html>