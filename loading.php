<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            text-align: center;
            height: 100%;
            overflow-y: hidden;
        <?php
            if(date('ymd', time()) == '190219'){
                echo "background: url(\"https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1550505358768&di=e6d0c7fe3b6f14ca1d07cbe07b314e0b&imgtype=0&src=http%3A%2F%2Fpic1.win4000.com%2Fwallpaper%2F3%2F589d1e4442f6a.jpg\") no-repeat fixed center;
                background-size: 100% 100%;";
            } ?>
        }

        #pay {
            position: absolute;
            top: 24%;
            left: 38%;
            height: 52%;
            z-index: -1;
        }

        #container {
        <?php
        if(date('ymd', time()) == '190219'){
            echo "opacity: 0;";
        }
        ?> transition: all 0.8s 0s;
        }
    </style>
</head>
<body>
<div id="container">
    <img id="pay" src="img/pay.jpg">
    <h2 style="margin: 1pc 0 0"><img src="img/logo.png" style="height: 70px;margin-bottom: -27px"> 影视爬虫v3.8<span
                style="font-size: 12px">(2019/1/24 18:21更新)</span></h2>
    <p>如果觉得本站好用，请将 <strong style="color: #F40">影视爬虫</strong> 推荐给您的朋友！</p>
    <div style="position: fixed;bottom: 0;width: 100%;margin: 0 auto">
        <p>科学上网15天仅需两元：<a href="http://jiasd.us/4297" target="_blank"><img
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