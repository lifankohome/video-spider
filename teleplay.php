<?php
/**
 * Created by PhpStorm.
 * User: lifanko  lee
 * Date: 2017/12/6
 * Time: 12:37
 */

use Cinema\Common;
use Cinema\Spider;

include('Cinema/Spider.php');
include('Cinema/Common.php');

$teleplays = Spider::getTeleplays($_SERVER["QUERY_STRING"]);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>电视剧 - 影视爬虫</title>
    <?php echo Common::SEO(); ?>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link type="text/css" rel="stylesheet" href="css/common.css">
    <link type="text/css" rel="stylesheet" href="css/video.css">
</head>
<body>
<?php
echo Common::background();
echo Common::$pined;

$ctl = Common::ctl();
if ($ctl['code'] <= 0) {
    echo $ctl['content'];
    echo "
</body>
</html>";
    die();
}
?>
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
    <div class="slider">
        <?php
        $slider = Spider::getSlider('teleplay');
        if ($slider[0]) {
            echo $slider[1];
        }
        ?>
    </div>
    <div class="s_r_line"></div>
    <!--排行榜-->
    <div class="rank">
        <?php
        $rank = Spider::getRank('teleplay');
        if ($rank[0]) {
            echo "<div class='title'>排行榜</div>";
            foreach ($rank[1] as $item) {
                if (empty($item['title'])) {
                    continue;
                }
                echo "<div class='item'><div>{$item['title']}</div><div><a href='play.php?play=t{$item['ent_id']}.html' style='color: chocolate'>播放</a></div></div>";
            }
        } else {
            echo file_get_contents('https://hpu.lifanko.cn/maxim');
        }
        ?>
    </div>
</div>

<div class="s_r">
    <!--筛选器-->
    <!--    --><?php //echo Spider::$filter; ?>
    <!--    <div class="s_r_line"></div>-->
    <!--访客地图-->
    <!--    <iframe class="map" src="Visits/map.html"></iframe>-->
</div>

<!--列表-->
<div class="videolist">
    <ul>
        <?php
        foreach ($teleplays as $teleplay) {
            echo "<li class='resList'><div class='imgTip'><p>{$teleplay['desc']}</p></div><a href='play.php?play={$teleplay['link']}' title='点击播放' target='_blank'>
                <img class='img' src='{$teleplay['cover']}' alt='{$teleplay['title']}'>
                <span class='update'>{$teleplay['tag']}</span>
                <span class='name'>《{$teleplay['title']}》</span>
            </a></li>";
        } ?>
    </ul>
    <div style="clear: both"></div>
    <!--提示搜索-->
    <?php echo Common::$bounce; ?>
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
