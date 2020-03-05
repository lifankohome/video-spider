<?php
include_once '../Cinema/Maxim.php';

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
            src: url("../font/PingFangSC-Regular.woff2");
        }

        @font-face {
            font-family: "SF Pro Text";
            src: url("../font/sf-pro-text_regular.woff2");
        }

        body {
            font-family: "PingFang SC", "SF Pro Text", "Helvetica Neue", "Helvetica", "Arial", sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
            height: 100%;
            overflow-y: hidden;
            text-shadow: 0 0 2px #d5d5d5;
            color: #000;
        <?php echo $css; ?>
        }
    </style>
</head>
<body>
<div id="container">
    <img src="../img/logo.png" style="height: 100px;margin-bottom: -40px" alt="">
    <h1 style="display: inline-block">
        影视爬虫<span style="font-size: 16px"> v4.5 2020/2/29 22:03更新</span>
    </h1>
    <p style="font-size: 20px">影视爬虫重金购买网址<strong style="color: #467cff;font-size: 30px">yspc.vip</strong>，一秒钟即可记住</p>
    <div id="ad" style="width: 60%;margin: 3pc auto 0 auto;font-size: 24px;">
        <h4 style="border-bottom: 1px #999 solid;padding-bottom: 5px">Today's Maxim<?php echo $festival; ?></h4>
        <p style="font-size: 24px;margin-top: -1pc;font-style: italic"><?php echo Maxim::get(); ?></p>
    </div>
    <h3 style="position: fixed;bottom: 0;width: 100%;margin: 0 auto;font-size: 16px;color: #5a0814;">
        VIP观影体验，尽在影视爬虫！
    </h3>
</div>
<script>
    var container = document.getElementById("container");

    setTimeout(function () {
        container.style.opacity = '1';
    }, 3000);
</script>
</body>
</html>