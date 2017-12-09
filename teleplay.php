<?php
/**
 * Created by PhpStorm.
 * User: lifanko  lee
 * Date: 2017/12/6
 * Time: 12:37
 */
use Cinema\Common;
use Cinema\Spider;

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

if (empty($_GET['cat'])) {
    $cat = "all";
} else {
    $cat = $_GET['cat'];
}

$teleplays = Spider::getTvs($cat);
$tvCat = Spider::getTvCat();

$catDir = '{"101":"\u8a00\u60c5","105":"\u4f26\u7406","109":"\u559c\u5267","108":"\u60ac\u7591","111":"\u90fd\u5e02","100":"\u5076\u50cf","104":"\u53e4\u88c5","107":"\u519b\u4e8b","103":"\u8b66\u532a","112":"\u5386\u53f2","106":"\u6b66\u4fa0","113":"\u79d1\u5e7b","102":"\u5bab\u5ef7","114":"\u60c5\u666f","115":"\u52a8\u4f5c","116":"\u52b1\u5fd7","117":"\u795e\u8bdd","118":"\u8c0d\u6218","110":"\u7ca4\u8bed","other":"\u5176\u4ed6"}';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>电视剧 - 影视爬虫</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link type="text/css" rel="stylesheet" href="css/teleplay.css">
    <link type="text/css" rel="stylesheet" href="css/header.css">
</head>
<body>
<header>
    <img src="img/logo.png">
    <?php echo Common::$header ?>
</header>
<div class="cat">
    <ul>
        <?php
        foreach ($tvCat as $key => $val) {
            echo "<li><a href='teleplay.php?cat={$key}'>$val</a></li>";
        }
        ?>
    </ul>
</div>
<div style="clear: both"></div>
<div class="teleplay">
    <h3>
        <?php
        if ($cat == 'all') {
            echo '热门电视剧';
        } else {
            echo '当前分类：' . json_decode($catDir, true)[$cat];
        }
        ?>
    </h3>
    <ul>
        <?php
        foreach ($teleplays as $teleplay) {
            echo "<li>
		    <a href='play.php?play={$teleplay['link']}' title='{$teleplay['actor']}' target='_blank'>
                <img src='{$teleplay['img']}' alt='{$teleplay['name']}'>
                <span id='update'>{$teleplay['update']}</span>
                <span id='name'>{$teleplay['name']}</span>
            </a>
        </li>";
        }
        ?>
    </ul>
</div>
<div style="clear: both"></div>
<footer>
    <?php
    echo Common::$QQGroup;
    echo Common::$footer;
    ?>
</footer>
<script async src="https://cdn.jsdelivr.net/gh/someartisans/analytics@0.1.0/dist/counter.min.js"></script>
</body>
</html>