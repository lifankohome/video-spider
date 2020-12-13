<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/25
 * Time: 13:16
 */

use Cinema\Spider;
use Cinema\Common;

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    function __autoload($class)
    {
        $file = $class . '.php';
        if (is_file($file)) {
            require_once($file);
        }
    }
} else {
    include_once('Cinema/Spider.php');
    include_once('Cinema/Common.php');
}

if (empty($_GET['max'])) { //显示的关键词数量，默认最多显示999个
    $max = 200;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>排行榜 - 影视爬虫</title>
    <?php echo Common::SEO(); ?>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link type="text/css" rel="stylesheet" href="css/common.css">
    <link type="text/css" rel="stylesheet" href="css/hot.css">
</head>
<body>
<header>
    <img src='img/logo.png' alt='logo' class="tiktok">
    <ul id="menu">
        <!--菜单-->
        <?php echo Common::$menu; ?>
        <!--搜索框-->
        <?php echo Common::$search_box; ?>
    </ul>
</header>
<?php
echo "<p class='ad'>" . Common::$ad . Common::visits() . "</p>";
echo Common::inform();
?>

<div style="background-color: rgba(255,255,255,0.51);padding: .2pc 1pc;margin-top: 1pc;border-radius: 5px;position: relative">
    <img src="img/wechat.jpg" alt="" style="position: absolute;right: 5px;top: 5px;height: 153px;cursor: pointer"
         onclick="window.location.href='img/wechat.jpg'">
    <h1>向我捐赠</h1>
    <p style="line-height: 25px">
        本人长期致力于影视爬虫应用开发，你的帮助是对我们最大的支持和动力！<br>
        影视爬虫一直在坚持不懈地努力，帮助更多人便捷地观看视频！<br>
        如果您觉得影视爬虫对你有所帮助，可使用微信扫描右侧二维码酌情向我捐赠~
    </p>

    <p>感谢
        <?php
        $filename = 'https://www.lifanko.cn/other/donate.json';
        $donate = file_get_contents($filename);
        $donate = json_decode($donate);

        $nickname = '';
        for ($i = 0; $i < 5; $i++) {
            $nickname .= '<span style="font-weight: 600;color: #F40;">' . $donate->nickname[$i] . '</span>, ';
        }
        $nickname = mb_substr($nickname, 0, mb_strlen($nickname) - 2);
        echo $nickname;
        ?>
        ……的捐赠支持!
    </p>
    <p>更多捐赠支持请点击 <a href="http://yspc.vip" target="_blank" style="color: #F40">这里</a> 查看。</p>
</div>
<div style="margin: 1pc 0;overflow: hidden;font-family: 'Microsoft YaHei UI',sans-serif">
    <div style="background-color: rgba(120,120,120,0.7);float: left;width: 50%;border-radius: 5px 0 0 5px">
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
    <div style="background-color: rgba(100,100,100,0.7);float: left;width: 50%;border-radius: 0 5px 5px 0">
        <div style="padding: 0 1pc 1pc 1pc;overflow: hidden">
            <h3>点击量排行榜：</h3>
            <div class="list">
                <ul style="list-style: decimal">
                    <?php echo Spider::getHistory($max, 'click'); ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<div style="clear: both;"></div>
<!--播放历史-->
<?php echo Common::$history; ?>
<!--问题反馈-->
<?php echo Common::$feedback; ?>
<footer>
    <?php
    echo Common::$tip;
    echo Common::$footer;
    ?>
</footer>
<script type="text/javascript" src="https://cdn.lifanko.cn/js/tip.min.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript">
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
            document.getElementById("searchBox").style.width = win_width + 190 + 'px';
            document.getElementById("holder").style.width = win_width + 212 + 'px';
        }
    }

    autoSize([]);  //初始化

    window.onresize = function () { //监听
        autoSize([]);
    };
</script>
<script type="text/javascript" src="js/tip.js"></script>
</body>
</html>