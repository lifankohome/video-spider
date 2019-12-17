<?php
include_once 'Cinema/Maxim.php';

use Cinema\Maxim;

$info = file_get_contents('https://www.lifanko.cn/festival2img/api.php');
$pos1 = mb_strpos($info, ':');
$pos2 = mb_strpos($info, 'http');

if ($pos2 !== false) {
    $festival = mb_substr($info, $pos1 + 1, $pos2 - $pos1 - 1);
    $festivalImgUrl = mb_substr($info, $pos2);
} else {
    $festival = mb_substr($info, $pos1 + 1);
    $festivalImgUrl = '';
}
if ($festival != 'No Festival') {
    $festival = '（' . $festival . '）';

    if (!empty($festivalImgUrl)) {
        $css = "    background: url($festivalImgUrl) no-repeat fixed center;
            background-size: 100% 100%;
        }

        #container {
            opacity: 0;
            transition: all 0.5s 0s;
        ";
    } else {
        $css = "";
    }
} else {
    $festival = '';
    $css = "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
        @font-face {
            font-family: "PingFang SC";
            src: url("font/PingFangSC-Regular.woff2");
        }

        @font-face {
            font-family: "SF Pro Text";
            src: url("font/sf-pro-text_regular.woff2");
        }

        body {
            font-family: "PingFang SC", "SF Pro Text", "Helvetica Neue", "Helvetica", "Arial", sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
            height: 100%;
            overflow-y: hidden;
            text-shadow: 0 0 3px black;
            color: white;
        <?php echo $css; ?>
        }
    </style>
</head>
<body>
<div id="container">
    <h2 style="margin: 1pc 0 0">
        <img src="img/logo.png" style="height: 70px;margin-bottom: -27px"> 影视爬虫v4.2
        <span style="font-size: 12px">（2019/10/23 23:27 重磅更新）</span>
    </h2>
    <p>
        如果觉得本站好用，请将 <strong style="color: #467cff;text-shadow: none">影视爬虫</strong> 添加到书签、推荐给您的朋友 或
        <span onmouseover="showDonate()" onmouseout="hideDonate()"
              style="color: #e22c1b;cursor: pointer;text-decoration: underline;text-shadow: none"
              id="donateTip">给作者打赏~
        </span>
        <img src="img/wechat.jpg" id="wechat" style="width: 24%;display: block;margin: -100pc auto 100px auto;">
    </p>
    <div id="ad" style="width: 60%;margin: 3pc auto 0 auto;font-size: 24px;">
        <h4 style="border-bottom: 1px #999 solid;padding-bottom: 5px">Today's Maxim<?php echo $festival; ?></h4>
        <p style="font-size: 24px;margin-top: -1pc;font-style: italic"><?php echo Maxim::get(); ?></p>
    </div>
    <div style="position: fixed;bottom: 0;width: 100%;margin: 0 auto;font-size: 14px;color: #000;text-shadow: none;">
        若影视无法播放请尝试切换右上方解析器 或 反馈站长：lifankohome@163.com
    </div>
</div>
<script>
    var container = document.getElementById("container");

    var wechat = document.getElementById("wechat");
    var ad = document.getElementById("ad");
    var donateTip = document.getElementById("donateTip");

    function showDonate() {
        ad.style.display = 'none';
        wechat.style.marginTop = 1 + 'pc';
        donateTip.innerText = "赏点小费（感谢老板~老板大气！！）";
    }

    function hideDonate() {
        ad.style.display = 'block';
        wechat.style.marginTop = -100 + 'pc';
        donateTip.innerText = "赏点小费~";
    }

    setTimeout(function () {
        container.style.opacity = '1';
    }, 3000);
</script>
</body>
</html>