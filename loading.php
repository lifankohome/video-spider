<?php
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
    <p>如果觉得本站好用，请将 <strong style="color: #467cff">影视爬虫</strong> 推荐给您的朋友！</p>
    <p style="margin-top: 100px">科学上网海外高速节点，新用户充值10美刀送50美刀，5刀每月1000GB流量 ↓<br><a
            href="https://www.vultr.com/?ref=7892094" target="_blank"><img
                src="img/vultr.png" style="background-color: #1669ba;padding: 10px;width: 50%;margin-top: 5px"></a></p>
    <div style="position: fixed;bottom: 0;width: 100%;margin: 0 auto">
        <p>科学上网15天仅需2元，800MB流量：<a href="http://jiasd.us/4297" target="_blank"><img
                    src="img/jasudu.png" style="margin-bottom: -10px"></a></p>
        <p style="margin-top: 2%">电影请点击左上方“立即播放”、电视剧选集后即可播放<br>（播放不了请尝试切换下方的解析器 或 反馈站长：lifankohome@163.com）</p>
    </div>
</div>
<script>
    var container = document.getElementById("container");

    setTimeout(function () {
        container.style.opacity = '1';
    }, 3000);
</script>
</body>
</html>