<?php
/**
 * Created by PhpStorm.
 * User: lifanko  lee
 * Date: 2017/12/6
 * Time: 12:37
 */
use Cinema\Common;
use Cinema\Spider;

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

if (!empty($_GET['url'])) {
    $url = $_GET['url'];
} else {
    die("<h2>无效的播放链接，将自动返回主页...<script>setTimeout(function() {window.location='index.php';},1500)</script></h2>");
}

?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VIP视频解析 - 影视爬虫</title>
    <?php
    echo Common::SEO();
    ?>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link type="text/css" rel="stylesheet" href="css/parse.css">
    <script src="https://cdn.bootcss.com/jquery/2.2.1/jquery.min.js"></script>
</head>
<body>
<header>
    <img src="img/logo.png">
    <?php echo Common::getHeader() ?>
</header>
<div class="container">
    <h3><?php
        echo "VIP视频解析——<a class='videoA' onclick='playUrl(\"$url\")' href='$url' target='ajax'>立即播放</a>";
        ?></h3>
    <div class="player">
        <iframe onload="iFrameResize()" id="video" src="loading.php"></iframe>
        <script type="text/javascript">
            var videoFrame = document.getElementById('video');  //全局使用
            function iFrameResize() {
                videoFrame.height = parseInt(videoFrame.scrollWidth / 16 * 9);
            }
        </script>
    </div>
    <?php echo Spider::$parser ?>
    <script type="text/javascript">
        var videoA = $(".videoA");
        videoA[0].href = 'javascript:void(0)';

        var videoLink = '';

        showParser();

        function showParser(){
            if (getCookie('parser') === "1") {
                document.getElementById('parser1').innerText = "默认解析器（使用中）";
                document.getElementById('parser2').innerText = "备用解析器";
            } else {
                document.getElementById('parser1').innerText = "默认解析器";
                document.getElementById('parser2').innerText = "备用解析器（使用中）";
            }
        }

        function vParser(url) {
            var parser;
            // 使用默认解析器解释时从cookie读取解析源地址，若为空则使用1号解析器；
            // 若指定了解析器则使用对应解析器解析，并更新cookie
            if (url === 'default') {
                parser = getCookie('parser');
                switch (parser) {
                    case '1':
                        parser = 1;
                        url = 'https://660e.com/?url=';
                        break;
                    case '2':
                        parser = 2;
                        url = 'https://jx.lache.me/cc/?url=';
                        break;
                    default:
                        parser = 1;
                        url = 'https://660e.com/?url=';
                        break;
                }
            } else {
                switch (url.substring(0, 15)) {
                    case 'https://660e.co':
                        parser = 1;
                        break;
                    case 'https://jx.lach':
                        parser = 2;
                        break;
                    default:
                        parser = 1;
                        break;
                }
                setCookie('parser', parser, 1); //保存用户当前使用的解析器
            }

            showParser();

            console.log('parser: ' + parser + ' url: ' + url + videoLink.href);
            videoFrame.src = url + videoLink.href;
        }

        function playUrl(url) {
            videoLink = url;
            vParser('default');
        }

        function setCookie(cookieKey, cookieValue, expireDays) {
            var expDate = new Date();
            expDate.setDate(expDate.getDate() + expireDays);
            //noinspection JSDeprecatedSymbols
            document.cookie = cookieKey + "=" + escape(cookieValue) +
                ((expireDays == null) ? "" : "; expires=" + expDate.toGMTString());
        }

        function getCookie(cookieKey) {
            var arr, reg = new RegExp("(^| )" + cookieKey + "=([^;]*)(;|$)");
            //noinspection JSDeprecatedSymbols
            return (arr = document.cookie.match(reg)) ? unescape(arr[2]) : null;
        }
    </script
</div>
<div class="support">
    <h2 style="margin-bottom: -1pc">支持以下网站VIP视频解析播放</h2>
    <ul>
        <li><img src="http://www.5ifxw.com/vip/2/aqylogo.png"></li>
        <li><img src="http://www.5ifxw.com/vip/2/youkulogo.png"></li>
        <li><img src="http://www.5ifxw.com/vip/2/letvlogo.png"></li>
        <li><img src="http://www.5ifxw.com/vip/2/qqlogo.png"></li>
        <li><img src="http://www.5ifxw.com/vip/2/tudoulogo.png"></li>
        <li><img src="http://www.5ifxw.com/vip/2/sohulogo.png"></li>
        <li><img src="http://www.5ifxw.com/vip/2/56logo.png"></li>
        <li><img src="http://www.5ifxw.com/vip/2/ku6logo.png"></li>
        <li><img src="http://www.5ifxw.com/vip/2/wasulogo.png"></li>
        <li><img src="http://www.5ifxw.com/vip/2/yinyuetailogo.png"></li>
        <li><img src="http://www.5ifxw.com/vip/2/hunantvlogo.png"></li>
        <li><img src="http://www.5ifxw.com/vip/2/sinalogo.png"></li>
        <li><img src="http://www.5ifxw.com/vip/2/163logo.png"></li>
        <li><img src="http://www.5ifxw.com/vip/2/baomihualogo.png"></li>
        <li><img src="http://www.5ifxw.com/vip/2/ifenglogo.png"></li>
        <li><img src="http://www.5ifxw.com/vip/2/cntvlogo.png"></li>
        <li><img src="http://www.5ifxw.com/vip/2/1905logo.png"></li>
        <li><img src="http://www.5ifxw.com/vip/2/tangdoulogo.png"></li>
    </ul>
</div>
<div style="clear: both"></div>
<?php
echo Common::$history;
?>
<footer>
    <?php
    echo Common::$QQGroup;
    echo Common::$footer;
    ?>
    <p style="font-size: 12px;text-align: right;margin-top: -25px">Cookie技术有效期:24h</p>
</footer>
<script type="text/javascript" src="https://cdn.lifanko.cn/js/tip.min.js"></script>
<script type="text/javascript">
    tip("欢迎使用影视爬虫！", "12%", 3000, "1", false);

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
        //Fixed player size: 16-9
        iFrameResize();
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
</script>
</body>
</html>