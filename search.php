<?php
/**
 * Created by PhpStorm.
 * User: lifanko  lee
 * Date: 2017/12/17
 * Time: 13:52
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
    <title>《<?php echo $kw; ?>》搜索 - 影视爬虫</title>
    <?php echo Common::SEO($kw); ?>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link type="text/css" rel="stylesheet" href="css/common.css">
    <link type="text/css" rel="stylesheet" href="css/search.css">
</head>
<body>
<header>
    <img src='img/logo.png' alt='logo' class="tiktok">
    <ul>
        <li><a href='hot.php'>首页</a></li>
        <li><a href='index.php'>电影</a></li>
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
<?php
echo "<p class='ad'>" . Common::$ad . Common::visits() . "</p>";
echo Common::inform();
?>

<div class="search">
    <h3>
        <?php
        if (empty($kw)) {
            echo '搜索量最多的视频：';
        } else {
            echo '《' . $kw . '》搜索结果：';
        } ?>
    </h3>
    <ul>
        <?php
        foreach ($search as $res) {
            echo "<li>
		    <a href='play.php?play={$res['link']}&s=search' title='{$res['desc']}' target='_blank'>
                <img class='img' src='{$res['img']}' alt='{$res['name']}'>
                <span id='type'>{$res['score']}</span>
                <span id='name'>{$res['name']}</span>
            </a></li>";
        } ?>
    </ul>
    <div style="clear: both"></div>
</div>
<!--播放历史-->
<?php echo Common::$history; ?>

<h3>若无法搜索到资源，可在下方留言~</h3>
<iframe style="background-color: transparent;padding: unset;height: 555px;border: none;width: 100%;margin: 5px 0 -10px;"
        src="https://www.lifanko.cn/chat/index.php?u=search&n=资源搜索不到"></iframe>
<footer>
    <?php
    echo Common::$tip;
    echo Common::$footer;
    ?>
</footer>
<script type="text/javascript" src="https://cdn.lifanko.cn/js/tip.min.js"></script>
<script type="text/javascript" src="js/app.js"></script>
<script>
    tip("《<?php echo $kw; ?>》搜索结果", "12%", 3000, "1", false);
</script>
</body>
</html>