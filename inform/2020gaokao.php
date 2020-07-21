<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>2020年高考出分时间及查询方式 - 影视爬虫</title>
    <meta name="keywords" content="影视爬虫,海量影视高清无广告在线观看,电影、电视剧、综艺、动漫免费在线播放,最新电影,最热电视剧,最火综艺,最新动漫">
    <meta name="description" content="影视爬虫为您提供最新最好看的影视内容,高清无广告资源每日更新,海量影视免费在线播放,最新电影,最热电视剧,最火综艺,最新动漫">
    <link rel="icon" href="https://www.lifanko.cn/video/favicon.ico" type="image/x-icon">
    <style>
        body {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            word-break: break-all;
            word-wrap: break-word;
        }

        table th {
            font-weight: bold;
            background: #efefef;
            padding: 6px;
            border: 1px solid #dfdfdf;
        }

        table td {
            padding: 6px;
            border: 1px solid #dfdfdf;
            line-height: 28px;
        }

        .place {
            font-weight: bold;
            color: #666;
        }

        .time {
            color: #e01717d6;
        }

        .link {
            color: #1787e0;
        }

        a, a:link, a:visited {
            color: #1787e0;
        }
    </style>
</head>
<body>
<?php

require_once '../Visits/Visits.php';

$Visits = new \Visits\Visits('', 'auto');

$url = "https://gaokao.chsi.com.cn/z/gkbmfslq/cjcx.jsp";

$dom = file_get_contents($url);

$start = strpos($dom, 'list:') + 5;

$end = strpos($dom, ']', $start);

$res = substr($dom, $start, $end - $start + 1);

echo "<script>var list = $res;</script>";
?>
<div>
    <img src="https://t4.chei.com.cn/gaokao/images/z/gkbmfslq/2020/banner.png?v=1507770100712" alt="gaokao"
         style="width: 100%">
</div>
<div style="margin: 1pc 10%;text-align: center">
    <h2>2020年高考成绩公布时间及查询方式汇总</h2>
    <div id="table"></div>
</div>
<footer>
    <?php
    echo $Visits->update();
    ?>
</footer>
<script>
    var table = document.getElementById("table");

    var regex = /(<([^>]+)>)/ig;

    var buffer = '<table>\n' +
        '        <tr>\n' +
        '            <th>省市</th>\n' +
        '            <th>出分时间</th>\n' +
        '            <th>成绩查询渠道</th>\n' +
        '        </tr>';
    for (var i = 0; i < list.length; i++) {
        var time = list[i].cfsj.replace(regex, "");
        var link = list[i].cjcxqd.replace('href="', 'href="https://gaokao.chsi.com.cn');

        if (link === '') {
            link = '-'
        }
        buffer += '<tr><td class="place">' + list[i].ss + '</td><td class="time">' + time + '</td><td class="link">' + link + '</td></tr>';
    }
    buffer += '</table>';

    table.innerHTML = buffer;
</script>
</body>
</html>
