<?php
/**
 * Created by PhpStorm.
 * User: lifanko  lee
 * Date: 2017/12/6
 * Time: 12:37
 */
use Cinema\Config;
use Cinema\Header;
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo Config::$title ?></title>
    <style>
        body{
            width: 80%;
            margin: 0 auto;
            font-family: "Microsoft JhengHei UI"
        }
        header{
            height: 70px;
            background-color: #5FB2D7;
            border-radius: 5px;
        }
        header img{
            height: 70px;
            float: left;
            margin: 0 1pc;
        }
        header, .movie ul{
            list-style: none;
            padding: 0;
            margin: 0;
            background-color: whitesmoke;
        }
        header ul li{
            float: left;
            list-style: none;
            line-height: 70px;
            padding: 0 20px;
        }
        header ul li a{
            color: #ff4645;
            font-weight: bold;
        }
        header ul li:hover{
            background-color: #ff4645;
        }
        header ul li:hover a{
            color: whitesmoke;
        }

        .movie ul li{
            float: left;
            width: 200px;
            height: 270px;
            overflow: hidden;
            margin: 0.5pc;
            padding: 0.5pc;
            position: relative;
            background-color: whitesmoke;
            border-radius: 5px;
        }
        .movie img{
            width: 200px;
            height: 270px;
        }
        #score{
            position: absolute;
            top: 0.5pc;
            left: 0.5pc;
            background-color: #ff4645;
            color: whitesmoke;
            font-size: 12px;
            padding: 1px 5px;
        }
        #year{
            position: absolute;
            right: 0.5pc;
            bottom: 0.5pc;
            background-color: #333;
            color: whitesmoke;
            font-size: 12px;
            padding: 1px 3px;
        }
        footer{
            clear: both;
        }
    </style>
</head>
<body>
<header>
    <img src="img/logo.png">
    <?php echo Header::$header ?>
</header>
<div class="movie">
    <h3 style="border-bottom: 1px #eee solid;margin-bottom: 0">热门电影推荐</h3>
    <ul>
    <?php
    $movies = Movies::getMovies(1);
    foreach($movies as $movie){
        if(empty($movie['score'])){
            $score = '无';
        }else{
            $score = $movie['score'];
        }
        echo "<li>
		    <a href='../play.php?play={$movie['link']}' title='{$movie['actor']}' target='_blank'>
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
    &copy; 2017 lifankohome
</footer>
</body>
</html>