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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>综艺 - 影视爬虫</title>
    <?php
    echo Common::SEO();
    ?>
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
        echo '当前分类：' . Spider::getPresentCat();
        ?>
    </h3>
    <ul>
        <?php
        foreach ($varieties as $variety) {
            echo "<li>
		    <a href='play.php?play={$variety['coverpage']}' title='{$variety['desc']}' target='_blank'>
                <img class='img' src='{$variety['cover']}' onerror=\"javascript:this.src='img/noCover.jpg'\" alt='{$variety['title']}'>
                <span id='update'>更新至:{$variety['tag']}</span>
                <span id='name'>{$variety['title']}</span>
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
<script src="js/browserMqtt.js"></script>
<script src="js/sCount.js"></script>
</body>
</html>