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

if (empty($_GET['kw'])) {
    $search = json_decode(saveInfo('defaultSearch'), true);

    if (empty($search)) {
        $search = Spider::search('老男孩');

        //历史为空为初始化记录操作
        recordSearch('老男孩', json_encode($search));
    }
} else {
    $kw = $_GET['kw'];
    $search = Spider::search($kw);

    //默认搜索也不统计流量
    recordSearch($kw, json_encode($search));
}

function recordSearch($hotWord, $list)
{
    $jsonHotSearch = saveInfo('searchHistory');

    if (!empty($jsonHotSearch)) {
        $arrHotSearch = json_decode($jsonHotSearch, true);  //解析为数组格式
        if (array_key_exists($hotWord, $arrHotSearch)) { //有记录则加一
            $arrHotSearch[$hotWord] += 1;

            if ($arrHotSearch[$hotWord] == max($arrHotSearch)) {    //搜索最多的作为默认列表
                saveInfo('defaultSearch', $list);
            }
        } else {  //无记录则在数组中创建
            $arrHotSearch[$hotWord] = 1;
        }

        $jsonHotSearch = json_encode($arrHotSearch);
    } else {  //文件为空
        $arrHotSearch = [$hotWord => 1];
        $jsonHotSearch = json_encode($arrHotSearch);
        saveInfo('defaultSearch', $list);
    }

    saveInfo('searchHistory', $jsonHotSearch);
}

function saveInfo($dir, $new = '')
{
    $filePath = $dir . '.txt';
    if (file_exists($filePath)) {
        if (empty($new)) {  //$new为空时是读取状态，不为空时为写入状态
            $fp = fopen($filePath, "r");
            $str = fread($fp, filesize($filePath));     //指定读取大小，这里把整个文件内容读取出来
            fclose($fp);

            return $str;
        } else {
            $fp = fopen($filePath, "w");
            flock($fp, LOCK_EX);
            fwrite($fp, $new);
            flock($fp, LOCK_UN);
            fclose($fp);

            return true;
        }
    }
    return false;
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