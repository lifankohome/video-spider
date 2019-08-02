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

if (empty($_GET['max'])) { //显示的关键词数量，默认最多显示999个
    $max = 666;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>影视排行榜 - 影视爬虫</title>
    <?php
    echo Common::SEO();
    ?>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link type="text/css" rel="stylesheet" href="css/hotsearch.css">
</head>
<body>
<header>
    <img src="img/logo.png">
    <?php echo Common::getHeader() ?>
</header>
<div style="margin: 2pc 0;overflow: hidden">
    <div style="background-image: linear-gradient( 35deg, #ABDCFF 10%, #0396FF 100%);float: left;width: 50%;border-radius: 5px 0 0 5px">
        <div style="padding: 0 1pc 1pc 1pc;overflow: hidden">
            <h3>搜索排行榜：</h3>
            <div class="list">
                <ul style="list-style: decimal">
                    <?php
                    echo Spider::getHistory($max);
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <div style="background-image: linear-gradient( 35deg, #FEB692 10%, #EA5455 100%);float: left;width: 50%;border-radius: 0 5px 5px 0">
        <div style="padding: 0 1pc 1pc 1pc;overflow: hidden">
            <h3>点击量排行榜：</h3>
            <div class="list">
                <ul style="list-style: decimal">
                    <?php
                    echo Spider::getHistory($max, 'clickHistory');
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<div style="clear: both;"></div>
<?php
echo Common::$history;
?>
<footer>
    <?php
    echo Common::$QQGroup;
    echo Common::$footer;
    ?>
</footer>
<script type="text/javascript" src="https://cdn.lifanko.cn/js/tip.min.js"></script>
<script type="text/javascript">
    tip("欢迎使用影视爬虫！", "12%", 2000, "1", false);

    //搜索功能
    var search = document.getElementById('searchBox');
    var searchText = document.getElementById('searchText');

    search.onkeyup = function () {
        if (search.value) {
            searchText.innerHTML = "<a href='search.php?kw=" + search.value + "' style='background-color: #444;color: white;margin-right: -1pc;border-top-right-radius: 5px;border-bottom-right-radius: 5px'>搜索</a>";
        } else {
            searchText.innerHTML = "<img src='img/yspc.png' style='margin: 0;height: 26px;position: relative;top: 7px'>";
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

    function autoSize(img) {
        //仅当有资源时才重新调整大小
        if (img.length) {
            var height = (img[0].width * 1.4).toFixed(0);   //取宽度
            for (var i = 0; i < img.length; i++) {  //根据比例统一高度
                img[i].style.height = height + 'px'
            }
        }

        //自动调整搜索框大小
        var win_width = document.body.clientWidth - 1050;

        if (win_width) {
            if (win_width > 125) {
                win_width = 125;
            }
            document.getElementById("searchBox").style.width = win_width + 175 + 'px';
        }
    }

    autoSize([]);  //初始化

    window.onresize = function () { //监听
        autoSize([]);
    };

    // 播放历史显示控制
    var his_frame = document.getElementById("fra-history");
    function showHistory() {
        his_frame.style.right = "0px";
    }
    function hideHistory() {
        his_frame.style.right = -300 + "px";
    }

    //百度统计
    var _hmt = _hmt || [];
    (function () {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?a258eee7e1b38615e85fde12692f95cc";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();

    console.log("你知道吗？《影视爬虫》为开源程序，于2017年12月6日开始编写并不断维护更新，至今已成长为一个稳定可靠的视频播放网站！\n开源地址：https://github.com/lifankohome/video-spider \n\n欢迎使用本开源代码建造属于自己的视频网站，任何人均可无限制地传播和使用本程序，但您需要在您的网站添加友情链接并告知lifankohome@163.com，否则，《影视爬虫》将通过合法手段撤回您对源代码的使用权。");
</script>
</body>
</html>