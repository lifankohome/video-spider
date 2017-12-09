<?php
/**
 * Created by PhpStorm.
 * User: lifanko  lee
 * Date: 2017/12/7
 * Time: 18:52
 */
use Cinema\Common;

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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>电视机 - 影视爬虫</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <style>
        body {
            width: 80%;
            /* 10/8倍播放器宽度*/
            min-width: 1362px;
            margin: 0 auto;
            font-family: "Microsoft JhengHei UI"
        }

        .tv h3 {
            border-bottom: 1px #eee solid;
        }

        .player {
            width: 90%;
            margin: 1pc auto;
        }

        iframe {
            width: 100%;
            min-width: 1090px;
            border: none;
            background-color: #eee;
            padding: 1pc;
            border-radius: 5px;
            margin-left: -1pc;
        }
    </style>
    <link type="text/css" rel="stylesheet" href="css/header.css">
</head>
<body>
<header>
    <img src="img/logo.png">
    <?php echo Common::$header ?>
</header>
<div class="tv">
    <h3>电视机——<a onclick="playTv()" href="javascript:void(0)">立即播放</a></h3>
    <div class="player">
        <iframe onload="iFrameLoad()" id="tv" src="loading.html" scrolling="no"></iframe>
        <script type="text/javascript">
            var videoFrame = document.getElementById('tv');  //全局使用
            function iFrameLoad() {
                videoFrame.height = videoFrame.contentWindow.document.body.scrollHeight;
            }

            function playTv(){
                document.getElementById("tv").src = "http://tv.bingdou.net/live.html";
            }
        </script>
    </div>
</div>
<footer>
    <?php
    echo Common::$QQGroup;
    echo Common::$footer;
    ?>
</footer>
<script async src="https://cdn.jsdelivr.net/gh/someartisans/analytics@0.1.0/dist/counter.min.js"></script>
</body>
</html>