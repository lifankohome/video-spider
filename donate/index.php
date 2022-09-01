<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>影视爬虫</title>
    <style>
        body {
            font-family: "华文细黑", sans-serif;
            width: 600px;
            margin: 1.5pc auto;
        }

        .tip {
            font-size: 18px;
            background-color: #aaaaaa;
            padding: 10px 5px;
            border-radius: 5px;
            width: 600px;
            margin: 1.5pc auto;
            color: black;
        }

        .enter {
            display: inline-block;
            text-decoration: none;
            font-size: 28px;
            margin: 0 auto;
            background-color: #497efd;
            color: whitesmoke;
            padding: 15px 30px;
            border-radius: 5px;
            box-shadow: 1px 3px 10px 0 #747474;
            transition: all 0.1s 0s;
        }

        .enter:hover {
            box-shadow: 0 0 0 0 #747474;
            background-color: #2965ff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            color: #333;
        }

        th, td {
            border: 1px solid #999;
            padding: 5px;
        }
    </style>
</head>
<body>
<header style="font-size: 12px;">
    &copy; 2020 lifanko <a href='http://www.beian.miit.gov.cn/' style='color: #333'>豫ICP备16040860号-3</a>
</header>
<div style="text-align: center">
    <div class="tip">
        <span style="color: #F40">“影视爬虫”</span>已经与<span style="color: #F40">“搜狗搜索”</span>达成合作协议，
        可在搜狗使用“关键字+影视爬虫”查找视频。
    </div>

    <a class="enter" href="https://www.lifanko.cn/video/index.php?s=yspc">点击进入影视爬虫</a>

    <div style="margin-top: 3pc;">
        <img src="https://www.lifanko.cn/video/img/wechat.jpg" alt="Donate" style="width: 200px">
        <p style="font-size: 14px;color: #ff5c46;font-weight: 600;">若影视爬虫给您的生活带来了方便，可向我打赏支持</p>
    </div>
    <div>
        <p style="background-color: #aaaaaa;color: #ad483a;padding: .5pc 0;margin: 0 auto;font-size: 20px">打赏列表</p>
        <table>
            <tr>
                <th>微信昵称</th>
                <th>&gt;￥0</th>
                <th>&gt;￥1</th>
                <th>&gt;￥5</th>
                <th>&gt;￥10</th>
                <th>&gt;￥20</th>
                <th>&gt;￥50</th>
                <th>&gt;￥100</th>
            </tr>

            <?php
            $donate = file_get_contents('donate.json');
            $donate = json_decode($donate, true);

            foreach ($donate['nickname'] as $key => $nickname) {
                echo "<tr>
                <td>$nickname</td>";

                $start = 0;
                if ($donate['money'][$key] < 1) {
                    $start = 0;
                } elseif ($donate['money'][$key] < 5) {
                    $start = 1;
                } elseif ($donate['money'][$key] < 10) {
                    $start = 2;
                } elseif ($donate['money'][$key] < 20) {
                    $start = 3;
                } elseif ($donate['money'][$key] < 50) {
                    $start = 4;
                } elseif ($donate['money'][$key] < 100) {
                    $start = 5;
                } else {
                    $start = 6;
                }

                for ($i = 0; $i < 7; $i++) {
                    if ($start == $i) {
                        echo "<td>√</td>";
                    } else {
                        echo "<td></td>";
                    }
                }
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</div>
</body>
</html>