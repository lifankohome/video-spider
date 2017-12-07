<?php
/**
 * Created by PhpStorm.
 * User: lifanko  lee
 * Date: 2017/12/6
 * Time: 12:37
 */
use Cinema\Common;
use Cinema\Movies;

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

$movies = Movies::getMovies(1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>电影 - 影视爬虫</title>
    <link type="text/css" rel="stylesheet" href="css/index.css">
    <link type="text/css" rel="stylesheet" href="css/header.css">
</head>
<body>
<header>
    <img src="img/logo.png">
    <?php echo Common::$header ?>
</header>
<div class="movie">
    <h3>热门电影</h3>
    <ul>
    <?php
    foreach($movies as $movie){
        if(empty($movie['score'])){
            $score = '无';
        }else{
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
    <?php echo Common::$footer ?>
</footer>
</body>
</html>