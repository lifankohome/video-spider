<?php
/**
 * Created by PhpStorm.
 * User: lifanko  lee
 * Date: 2017/12/9
 * Time: 21:29
 */

use Cinema\Common;
use Cinema\Spider;

include('Cinema/Spider.php');
include('Cinema/Common.php');

$varieties = Spider::getVarieties($_SERVER["QUERY_STRING"]);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>综艺 - 影视爬虫</title>
    <?php echo Common::SEO(); ?>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link type="text/css" rel="stylesheet" href="css/common.css">
    <link type="text/css" rel="stylesheet" href="css/video.css">
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
<!--宣传栏-->
<?php echo "<p class='ad'>" . Common::$ad . Common::visits() . "</p>"; ?>

<!--广告栏-->
<?php echo Common::inform(); ?>

<div class="s_r">
    <!--轮播图-->
    <?php echo Spider::getSlider('zongyi'); ?>
    <div class="s_r_line"></div>
    <!--排行榜-->
    <?php echo Spider::getRank(); ?>
</div>

<div class="s_r">
    <!--筛选器-->
    <?php echo Spider::$filter; ?>
    <div class="s_r_line"></div>
    <!--访客地图-->
    <iframe class="map" src="Visits/map.html"></iframe>
</div>

<!--列表-->
<div class="videolist">
    <ul>
        <?php
        foreach ($varieties as $variety) {
            echo "<li class='resList'><div class='imgTip'><p>{$variety['desc']}</p></div><a href='play.php?play={$variety['coverpage']}' title='点击播放' target='_blank'>
                <img class='img' src='{$variety['cover']}' alt='{$variety['title']}'>
                <span class='update'>更新至:{$variety['tag']}</span>
                <span class='name'>{$variety['title']}</span>
            </a></li>";
        } ?>
    </ul>
    <div style="clear: both"></div>
    <!--提示搜索-->
    <?php echo Common::$bounace; ?>
</div>
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
<script type="text/javascript" src="js/app.js"></script>
<script type="text/javascript" src="js/tip.js"></script>
</body>
</html>