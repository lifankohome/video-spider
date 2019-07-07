<?php
include_once 'Cinema/Maxim.php';
use Cinema\Maxim;

$festival = file_get_contents('https://www.lifanko.cn/festival2img/api.php');

$festival = mb_substr($festival, mb_strpos($festival, ':') + 1);
if ($festival != 'No Festival') {
    $festival = '（' . $festival . '）';
} else {
    $festival = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
        body {
            /*noinspection CssNoGenericFontName*/
            font-family: "Microsoft YaHei UI Light";
            margin: 0;
            padding: 0;
            text-align: center;
            height: 100%;
            overflow-y: hidden;
        }
    </style>
</head>
<body>
<div id="container">
    <h2 style="margin: 1pc 0 0">
        <img src="img/logo.png" style="height: 70px;margin-bottom: -27px"> 影视爬虫v4.0
        <span style="font-size: 12px">(2019/6/30 17:27更新 全站已支持HTTPS)</span>
    </h2>
    <p>
        如果觉得本站好用，请将 <strong style="color: #467cff">影视爬虫</strong> 推荐给您的朋友 或 <strong
            style="color: #FF7c55">购买下方商品</strong> 或直接
        <span onmouseover="showDonate()" onmouseout="hideDonate()"
              style="color: #e22c1b;cursor: pointer;text-decoration: underline"
              id="donateTip">给作者打赏~
        </span>
        <img src="img/wechat.jpg" id="wechat" style="width: 24%;display: block;margin: -100pc auto 100px auto;">
    </p>
    <div id="ad" style="width: 60%;margin: -4pc auto 0 auto;font-size: 24px;">
        <h4 style="border-bottom: 1px #999 solid;">今日<?php echo $festival; ?>箴言</h4>
        <p style="font-size: 15px;margin-top: -1.5pc"><?php echo Maxim::get(); ?></p>
    </div>
    <div
        style="border: 1px solid #777;background-color: #a9aeb3;width: 80%;margin: 0 auto;padding: .2pc 1pc;border-radius: 5px">
        <div style="overflow-y: hidden;display: inline-block">
            <script type="text/javascript">var jd_union_pid = "1845068976";
                var jd_union_euid = "";</script>
            <script type="text/javascript" src="//ads-union.jd.com/static/js/union.js"></script>
        </div>
        <div style="overflow-y: hidden;display: inline-block">
            <script type="text/javascript">var jd_union_pid = "1845264202";
                var jd_union_euid = "";</script>
            <script type="text/javascript" src="//ads-union.jd.com/static/js/union.js"></script>
        </div>
        <div style="overflow-y: hidden;display: inline-block">
            <script type="text/javascript">var jd_union_pid = "1845068995";
                var jd_union_euid = "";</script>
            <script type="text/javascript" src="//ads-union.jd.com/static/js/union.js"></script>
        </div>
        <div style="overflow-y: hidden;display: inline-block">
            <script type="text/javascript">var jd_union_pid = "1845284046";
                var jd_union_euid = "";</script>
            <script type="text/javascript" src="//ads-union.jd.com/static/js/union.js"></script>
        </div>
        <div style="overflow-y: hidden;display: inline-block">
            <p style="width: 120px;background-color: white;font-size: 12px;text-align: justify;padding: 4px 5px;margin: 0">
                JBL音乐金砖，音质3颗星
            </p>
            <p style="width: 120px;background-color: white;font-size: 12px;text-align: justify;padding: 4px 5px;margin: 0">
                JBL205BT蓝牙耳机，音质4颗星，舒适度5颗星，延迟4.5颗星(加vx:lifanko，仅需￥269即可购买)
            </p>
            <p style="width: 120px;background-color: white;font-size: 12px;text-align: justify;padding: 4px 5px;margin: 0">
                漫步者W205BT，音质4.5颗星，舒适度3颗星，延迟2颗星
            </p>
            <p style="width: 120px;background-color: white;font-size: 12px;text-align: justify;padding: 4px 5px;margin: 0">
                魅族Flow圈铁耳机，音质5颗星，舒适度4颗星
            </p>
        </div>
    </div>
    <p>电影请点击左上方“立即播放”、电视剧选集后即可播放<br>播放不了请尝试切换下方的解析器 或 反馈站长：lifankohome@163.com</p>
</div>
<script>
    var container = document.getElementById("container");

    var wechat = document.getElementById("wechat");
    var ad = document.getElementById("ad");
    var donateTip = document.getElementById("donateTip");

    function showDonate() {
        ad.style.display = 'none';
        wechat.style.marginTop = 1 + 'pc';
        donateTip.innerText = "给作者打赏~（感谢老板~老板大气！！）";
    }

    function hideDonate() {
        ad.style.display = 'block';
        wechat.style.marginTop = -100 + 'pc';
        donateTip.innerText = "给作者打赏~";
    }

    setTimeout(function () {
        container.style.opacity = '1';
    }, 3000);
</script>
</body>
</html>