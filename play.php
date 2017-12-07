<?php
use Cinema\Common;
use Cinema\Movies;

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
    $intro = $intro[1][0];
}
$name = $name[1][0];
$link = str_replace("http://cps.youku.com/redirect.html?id=0000028f&url=", "", $link[2][0]);

?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="<?php echo $name; ?>-播放页">
    <title>播放</title>
    <style>
        body {
            width: 80%;
            min-width: 960px;
            margin: 0 auto;
            font-family: "Microsoft JhengHei UI"
        }

        iframe {
            width: 100%;
            border: none;
            background-color: #eee;
            padding: 1pc;
            border-radius: 5px;
            margin-left: -1pc;
        }

        .player {
            width: 80%;
            margin: 1pc auto;
        }

        button{
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
    <h3><?php echo $name . "——<a id='videoA' href='$link' target='ajax'>立即播放</a>" ?></h3>
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
    <?php echo Movies::$parser ?>
    <script type="text/javascript">
        var videoA = $("#videoA");
        var videoLinkBuffer = [];
        for (var i = 0; i < videoA.length; i++) {
            videoLinkBuffer.push(videoA[i].href);
            videoA[i].href = 'javascript:void(0)';
            videoA.eq(i).attr('onclick', 'playUrl(\'' + videoLinkBuffer[i] + '\')');
        }
        function vParser(url) {
            videoFrame.src = url + videoLink.href;
        }

        function playUrl(mp4url) {
            videoLink.href = mp4url;
            vParser('http://api.wlzhan.com/sudu/?url=');    //默认使用线路一解析
        }
    </script>
    <h3 style="margin-bottom: 5px">剧情简介：</h3>
    <p style="margin-top: 0"><?php echo $intro; ?></p>

    <p style="text-align: center;color: #F00;font-size: 12px;background: #eee;padding: 6px 2px;border-radius: 2px;">
        <a style="color: black" href="http://shang.qq.com/wpa/qunwpa?idkey=26b6f96fa0f605cffd37006d220988904e4d9cbf87f2b33f65ebf7b31b6bcd51"
           target="_blank">点击加入影视爬虫QQ交流群：517831979</a></p>
</div>
<footer>
<?php echo Common::$footer ?>
</footer>
</body>
</html>