<?php
/**
 * Created by PhpStorm.
 * User: lifanko  lee
 * Date: 2017/12/17
 * Time: 13:52
 */

use Cinema\Common;
use Cinema\Spider;

include('Cinema/Spider.php');
include('Cinema/Common.php');

if (empty($_GET['kw'])) {
    $search = json_decode(Spider::saveInfo('search_d'), true);

    if (empty($search)) {
        $search = Spider::search('老男孩');    //默认搜索

        //历史为空则初始化记录操作
        Spider::recordSearch('老男孩', json_encode($search));

        $kw = '老男孩';
    } else {
        $kw = '最热影视';
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
        <?php echo Common::$search_box; ?>
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
    <div class="result">
        <?php
        foreach ($search as $res) {
            echo '<div class="item">
                <div class="label">' . $res['label'] . '</div>
                <div class="cover"><img src="' . $res['cover'] . '"></div>
                <div class="info">
                    <div class="title">《' . $res['title'] . '》<span>地区：' . $res['area'] . '</span>';

            if ($res['total']) {
                echo '<span>剧集：' . $res['total'] . '</span>';
            }
            echo '</div>
                    <div>导演：' . $res['director'] . '</div>
                    <div>演员：' . $res['actor'] . '</div>
                    <div class="intro">简介：' . $res['description'] . '</div>
                    <div class="play_btn"><a href="play.php?play=' . $res['link'] . '">立即播放</a></div>
                </div>
            </div>';
        }

        if (empty($search)) {
            echo "<div class='no_data'>未搜索到相关内容，请 更换关键词 或 稍后再试试 吧</div>";
        }
        ?>
    </div>
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
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/app.js"></script>
<script>
    tip("《<?php echo $kw; ?>》搜索结果", "12%", 3000, "1", false);
</script>
</body>
</html>