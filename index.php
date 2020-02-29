<?php
/**
 * Created by PhpStorm.
 * User: lifanko  lee
 * Date: 2017/12/6
 * Time: 12:37
 */

use Cinema\Common;
use Cinema\Spider;

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

if (empty($_GET['cat'])) {
    $cat = "all";
} else {
    $cat = $_GET['cat'];
}

$movies = Spider::getMovies($cat);
$moviesCat = Spider::getMoviesCat();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>电影 - 影视爬虫</title>
    <?php echo Common::SEO(); ?>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link type="text/css" rel="stylesheet" href="css/common.css">
    <link type="text/css" rel="stylesheet" href="css/video.css">
</head>
<body>
<header>
    <img src='img/logo.png' alt='logo'>
    <ul>
        <li><a href='hot.php'>首页</a></li>
        <li class="active"><a href='index.php'>电影</a></li>
        <li><a href='variety.php'>综艺</a></li>
        <li><a href='teleplay.php'>电视剧</a></li>
        <li><a href='anime.php'>动漫</a></li>
        <li><a href='other/about.html'>说明</a></li>
        <li id='searchli'>
            <label for='searchBox'></label><input type='text' id='searchBox' placeholder='输入关键词 - 黑科技全网搜索'>
            <span id='searchText'><img src='img/yspc.png' style='' alt='yspc'></span>
        </li>
    </ul>
</header>
<!--公告-->
<?php echo Common::$ad; ?>

<div class="cat">
    <ul>
        <?php
        foreach ($moviesCat as $key => $val) {
            echo "<li><a href='index.php?cat={$key}'>$val</a></li>";
        } ?>
    </ul>
</div>
<div style="clear: both"></div>
<!--影视推荐轮播图-->
<?php echo Spider::getSlider('dianying'); ?>

<!--影视排行榜-->
<?php echo Spider::getRank(); ?>

<!--影视列表-->
<div class="videolist">
    <h3><?php echo '当前分类：' . Spider::getPresentCat(); ?></h3>
    <ul>
        <?php
        foreach ($movies as $movie) {
            echo "<li class='resList'><div class='imgTip'><p>{$movie['desc']}</p></div><a href='play.php?play={$movie['coverpage']}' title='点击播放' target='_blank'>
                <img class='img' src='{$movie['cover']}' onerror=\"javascript:this.src='img/noCover.jpg'\" alt='{$movie['title']}'>
                <span id='score'>{$movie['point']}</span>
                <span id='name'>{$movie['tag']} {$movie['title']}</span>
            </a></li>";
        } ?>
    </ul>
</div>
<div style="clear: both"></div>
<!--播放历史-->
<?php echo Common::$history; ?>

<footer>
    <?php
    echo Common::$tip;
    echo Common::$footer;
    ?>
</footer>
<script type="text/javascript" src="https://cdn.lifanko.cn/js/tip.min.js"></script>
<script type="text/javascript" src="js/app.js"></script>
<script>
    tip("影视爬虫 - 电影频道", "12%", 3000, "1", false);
</script>
</body>
</html>