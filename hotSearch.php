<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/25
 * Time: 13:16
 */

use Cinema\Spider;
use Cinema\Common;

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {   //windows系统
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
} else {    //非windows系统（linux）
    include_once('Cinema/Spider.php');
    include_once('Cinema/Common.php');
}

if (empty($_GET['max'])) { //显示的关键词数量，默认最多显示50个
    $max = 50;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>电影 - 影视爬虫</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <style>
        body {
            width: 80%;
            min-width: 960px;
            margin: 0 auto;
            font-family: "Microsoft JhengHei UI"
        }

        h3 {
            border-bottom: 1px #eee solid;
            line-height: 2pc;
            margin-bottom: 0;
        }

        .list li {
            float: left;
            background-color: #bbb;
            margin: .5pc 2pc .5pc 0;
            border-radius: 3px;
            transition: all 0.3s 0s;
        }

        .list a {
            display: inline-block;
            padding: 0.5pc 1pc;
            color: #9c2900;
            text-decoration: none;
        }

        .list li:hover {
            background-color: #555;
        }

        .list li:hover a {
            text-decoration: underline;
            color: whitesmoke;
        }

    </style>
    <link type="text/css" rel="stylesheet" href="css/header.css">
</head>
<body>
<header>
    <img src="img/logo.png">
    <?php echo Common::getHeader() ?>
</header>
<h3>搜索排行榜：</h3>
<div class="list">
    <ul style="list-style: decimal">
        <?php
        Spider::getHistory($max);
        ?>
    </ul>
</div>
<div style="clear: both"></div>
<h3>点击量排行榜：</h3>
<div class="list">
    <ul style="list-style: decimal">
        <?php
        Spider::getHistory($max, 'clickHistory');
        ?>
    </ul>
</div>
<div style="clear: both"></div>
<footer>
    <?php
    echo Common::$QQGroup;
    echo Common::$footer;
    ?>
</footer>
<script type="text/javascript" src="http://cdn.lifanko.cn/tip10.min.js"></script>
<script type="text/javascript">
    tip("影视爬虫祝福您：狗年大吉！", "12%", 2000, "1", false);

    //搜索功能
    var search = document.getElementById('searchBox');
    var searchText = document.getElementById('searchText');

    search.onkeyup = function () {
        if (search.value) {
            searchText.innerHTML = "<a href='search.php?kw=" + search.value + "' style='background-color: #444;margin-right: -1pc'>搜索</a>";
        } else {
            searchText.innerText = '影视爬虫';
        }
    };

    //回车搜索
    document.onkeydown = function (e) {
        var theEvent = window.event || e;
        var code = theEvent.keyCode || theEvent.which;
        if (code === 13) {
            if (search.value) {
                window.location.href = "search.php?kw=" + search.value;
                tip("正在搜索：" + search.value, "12%", 2000, "1", true);
            } else {
                window.location.href = "search.php";
                tip("正在搜索最热视频", "12%", 2000, "1", true);
            }
        }
    };

    //百度统计
    var _hmt = _hmt || [];
    (function () {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?a258eee7e1b38615e85fde12692f95cc";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>
</body>
</html>