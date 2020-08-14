<?php
if (empty($_GET['kw'])) {
    die('{}');
}

$kw = $_GET['kw'];
$kw = str_replace(' ','',$kw);

echo getRecord($kw);

function getRecord($keyword, $num = 5)
{
    $keywords = file_get_contents('Cinema/searchHistory.txt');
    $keywords = str_replace(' ', '', $keywords);
    $keywords = json_decode($keywords, true);
    arsort($keywords);
    $ret = array_keys($keywords);

    $res = [];
    $i = 0;
    foreach ($ret as $val) {
        if (mb_strstr($val, $keyword) !== false) {
            array_push($res, $val);
            if (++$i == $num) {
                break;
            }
        }
    }

    return pretty($res, $keyword);
}

function pretty($res, $tar)
{
    return json_encode($res);
}
