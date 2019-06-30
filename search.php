<?php
/**
 * Created by PhpStorm.
 * User: lifanko  lee
 * Date: 2017/12/17
 * Time: 13:52
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

if (empty($_GET['kw'])) {
    $search = json_decode(Spider::saveInfo('defaultSearch'), true);

    if (empty($search)) {
        $search = Spider::search('老男孩');    //默认搜索

        //历史为空则初始化记录操作
        Spider::recordSearch('老男孩', json_encode($search));

        $kw = '老男孩';
    } else {
        $kw = '最新影视';
    }
} else {
    $kw = $_GET['kw'];
    $search = Spider::search($kw);

    //不统计无效关键字
    Spider::recordSearch($kw, json_encode($search));
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>搜索 - 影视爬虫</title>
    <?php
    echo Common::SEO($kw);
    ?>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link type="text/css" rel="stylesheet" href="css/search.css">
</head>
<body>
<header>
    <img src="img/logo.png">
    <?php echo Common::getHeader() ?>
</header>
<div class="search">
    <h3>
        <?php
        if (empty($kw)) {
            echo '搜索量最多的视频：';
        } else {
            echo '《' . $kw . '》搜索结果：';
        }
        ?>
    </h3>
    <ul>
        <?php
        foreach ($search as $res) {
            echo "<li>
		    <a href='play.php?play={$res['link']}&s=search' title='{$res['name']}' target='_blank'>
                <img class='img' src='{$res['img']}' onerror=\"javascript:this.src='img/noCover.jpg'\" alt='{$res['name']}'>
                <span id='type'>{$res['type']}</span>
                <span id='name'>{$res['name']}</span>
            </a></li>";
        }
        ?>
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
</footer>
<script type="text/javascript" src="https://cdn.lifanko.cn/js/tip.min.js"></script>
<script type="text/javascript" src="js/app.js"></script>
<script src="https://cdn.lifanko.cn/js/browserMqtt.js"></script>
<script src="js/sCount.js"></script>
</body>
</html>