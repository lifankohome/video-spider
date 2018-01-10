<?php
/**
 * Created by PhpStorm.
 * User: lifanko  lee
 * Date: 2017/12/17
 * Time: 13:52
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

if(empty($_GET['kw'])){
    $kw = '老男孩';
}else{
    $kw = $_GET['kw'];
}

$search = Spider::search($kw);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>搜索 - 影视爬虫</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link type="text/css" rel="stylesheet" href="css/search.css">
    <link type="text/css" rel="stylesheet" href="css/header.css">
</head>
<body>
<header>
    <img src="img/logo.png">
    <?php echo Common::$header ?>
</header>
<div class="search">
    <h3>
        <?php
        if (empty($kw)) {
            echo '热门电影推荐：';
        } else {
            echo '《' . $kw . '》搜索结果：';
        }
        ?>
    </h3>
    <ul>
        <?php
        foreach ($search as $res) {
            echo "<li>
		    <a href='play.php?play={$res['link']}' title='{$res['name']}' target='_blank'>
                <img class='img' src='{$res['img']}' alt='{$res['name']}'>
                <span id='type'>{$res['type']}</span>
                <span id='name'>{$res['name']}</span>
            </a></li>";
        }
        ?>
    </ul>
</div>
<div style="clear: both"></div>
<footer>
    <?php echo Common::$footer ?>
</footer>
<script type="text/javascript" src="js/tip.min.js"></script>
<script type="text/javascript" src="js/app.js"></script>
</body>
</html>