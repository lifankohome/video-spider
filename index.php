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

$movies = Spider::getMovies($cat);
$moviesCat = Spider::getMoviesCat();

$catDir = '{"103":"\u559c\u5267","100":"\u7231\u60c5","106":"\u52a8\u4f5c","102":"\u6050\u6016","104":"\u79d1\u5e7b","112":"\u5267\u60c5","105":"\u72af\u7f6a","113":"\u5947\u5e7b","108":"\u6218\u4e89","115":"\u60ac\u7591","107":"\u52a8\u753b","117":"\u6587\u827a","101":"\u4f26\u7406","118":"\u7eaa\u5f55","119":"\u4f20\u8bb0","120":"\u6b4c\u821e","121":"\u53e4\u88c5","122":"\u5386\u53f2","123":"\u60ca\u609a","other":"\u5176\u4ed6"}';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>电影 - 影视爬虫</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link type="text/css" rel="stylesheet" href="css/index.css">
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
        foreach ($moviesCat as $key => $val) {
            echo "<li><a href='index.php?cat={$key}'>$val</a></li>";
        }
        ?>
    </ul>
</div>
<div style="clear: both"></div>
<div class="movie">
    <h3>
        <?php
        if ($cat == 'all') {
            echo '热门电影';
        } else {
            echo '当前分类：' . json_decode($catDir, true)[$cat];
        }
        ?>
    </h3>
    <ul>
        <?php
        foreach ($movies as $movie) {
            if (empty($movie['score'])) {
                $score = '无';
            } else {
                $score = $movie['score'];
            }
            echo "<li>
		    <a href='play.php?play={$movie['link']}' title='{$movie['actor']}' target='_blank'>
                <img src='{$movie['img']}' alt='{$movie['name']}'>
                <span id='score'>{$score}</span>
                <span id='year'>{$movie['year']}</span>
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