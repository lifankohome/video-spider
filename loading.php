<?php
include_once 'Cinema/Maxim.php';
use Cinema\Maxim;

$festival = file_get_contents('http://ali.lifanko.cn/festival2img/index.php?day=' . date('Ymd', time()));

if (!empty($festival)) {
    $src = explode(':http', $festival);
    $lightness = mb_substr($src[0], 0, 1);
    $src = 'http' . $src[1];
    if ($lightness == '0') {
        $css = "    text-shadow: 0px 0px 5px black;
            color: white;";
    } else if ($lightness == '1') {
        $css = "    text-shadow: 0px 0px 5px white;
            color: black;";
    } else {
        $css = '    ';
    }

    $css .= "
            background: url($src) no-repeat fixed center;
            background-size: 100% 100%;
        }

        #container {
            opacity: 0;
            transition: all 0.8s 0s;
        }
";
} else {
    $css = '';
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
        <?php
        if(!empty($css)){
        echo $css;
        }else{
        echo "}
";
        }
        ?>
    </style>
</head>
<body>
<div id="container">
    <h2 style="margin: 1pc 0 0"><img src="img/logo.png" style="height: 70px;margin-bottom: -27px"> 影视爬虫v3.9<span
            style="font-size: 12px">（2019/2/21 11:46更新）</span></h2>
    <p>如果觉得本站好用，请将 <strong style="color: #467cff">影视爬虫</strong> 推荐给您的朋友 或
        <span onmouseover="showDonate()" onmouseout="hideDonate()" style="color: #e22c1b;cursor: pointer;text-decoration: underline"
              id="donateTip">给作者打赏~</span><img src="img/wechat.jpg" id="wechat" style="width: 24%;display: block;margin: -100pc auto 100px auto;"></p>
    <div id="ad" style="width: 60%;margin: -20px auto 0 auto;font-size: 24px;"><h4 style="border-bottom: 1px #999 solid;">今日箴言</h4><p style="font-size: 15px"><?php echo Maxim::get(); ?></p></div>
    <div style="position: fixed;bottom: 0;width: 100%;margin: 0 auto">
        <p style="margin-top: 2%">电影请点击左上方“立即播放”、电视剧选集后即可播放<br>播放不了请尝试切换下方的解析器 或 反馈站长：lifankohome@163.com</p>
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