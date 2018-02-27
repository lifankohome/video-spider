<?php
/**
 * Created by PhpStorm.
 * User: lifanko  lee
 * Date: 2017/12/9
 * Time: 21:29
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

if (empty($_GET['cat'])) {
    $cat = "all";
} else {
    $cat = $_GET['cat'];
}

$varieties = Spider::getVarieties($cat);
$varietyCat = Spider::getVarietyCat();

$catDir = '{"101":"\u9009\u79c0","102":"\u516b\u5366","103":"\u8bbf\u8c08","104":"\u60c5\u611f","105":"\u751f\u6d3b","106":"\u665a\u4f1a","107":"\u641e\u7b11","108":"\u97f3\u4e50","109":"\u65f6\u5c1a","110":"\u6e38\u620f","111":"\u5c11\u513f","112":"\u4f53\u80b2","113":"\u7eaa\u5b9e","114":"\u79d1\u6559","115":"\u66f2\u827a","116":"\u6b4c\u821e","117":"\u8d22\u7ecf","118":"\u6c7d\u8f66","119":"\u64ad\u62a5","other":"\u5176\u4ed6"}';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>综艺 - 影视爬虫</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link type="text/css" rel="stylesheet" href="css/variety.css">
    <link type="text/css" rel="stylesheet" href="css/header.css">
</head>
<body>
<header>
    <img src="img/logo.png">
    <?php echo Common::getHeader() ?>
</header>
<div class="cat">
    <ul>
        <?php
        foreach ($varietyCat as $key => $val) {
            echo "<li><a href='variety.php?cat={$key}'>$val</a></li>";
        }
        ?>
    </ul>
</div>
<div style="clear: both"></div>
<div class="teleplay">
    <h3>
        <?php
        if ($cat == 'all') {
            echo '热门综艺';
        } else {
            echo '当前分类：' . json_decode($catDir, true)[$cat];
        }
        ?>
    </h3>
    <ul>
        <?php
        foreach ($varieties as $variety) {
            echo "<li>
		    <a href='play.php?play={$variety['link']}' title='{$variety['actor']}' target='_blank'>
                <img class='img' src='{$variety['img']}' alt='{$variety['name']}'>
                <span id='update'>更新:{$variety['update']}</span>
                <span id='name'>{$variety['name']}</span>
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
<script type="text/javascript" src="http://cdn.lifanko.cn/tip10.min.js"></script>
<script type="text/javascript" src="js/app.js"></script>
</body>
</html>