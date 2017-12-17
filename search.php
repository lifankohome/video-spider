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
    <title>搜索 - 影视爬虫</title>
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
                <img src='{$res['img']}' alt='{$res['name']}'>
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
</body>
</html>