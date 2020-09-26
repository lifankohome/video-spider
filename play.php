<?php
/**
 * Created by PhpStorm.
 * User: lifanko  lee
 * Date: 2017/12/6
 * Time: 12:37
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

if (!empty($_GET['play'])) {
    $play = $_GET['play'];
    if (substr($play, 0, 5) == 'aHR0c') {
        $player = base64_decode($play);
    } else {
        // 此链接被搜狗高权重收录
        if ($play == '/m/fqflYhH5RHP0UB.html') {
            $play = '/m/hKrkakb6SHnASh.html';
        }
        $player = 'http://www.360kan.com' . $play;
    }
} else {
    die("<h2>无效的播放链接，将自动返回主页...<script>setTimeout(function() {window.location='index.php';},1500)</script></h2>");
}

$dom = Spider::curl_get_contents($player);

$nameDom = '/<h1>(.*?)<\/h1>/';
$introDom = '/style="display:none;"><span>简介 ：<\/span><p class="item-desc">([\s\S]*)<a href="#"/';
$linkDom = '/<a data-daochu=(.*?) class=(.*?) href="([\S]+)"/';
$albumDom = '/class="g-playicon s-cover-img" data-daochu="to=(.*?)\s+<img src="(.*?)">/';

preg_match_all($nameDom, $dom, $name);
preg_match_all($introDom, $dom, $intro);
preg_match_all($linkDom, $dom, $link);
preg_match_all($albumDom, $dom, $album);

$name = $name[1][0];

$intro = empty($intro[1][0]) ? "暂无" : $intro[1][0];
// 去除多余的缩进（空格）
$intro = str_replace('　', '', $intro);

$sets = array();
if (empty($link[3][0])) {
    $setsADom = '/<a data-num="(.*?)"\s*data-daochu="to=(.*?)" href="(.*?)"/';
    preg_match_all($setsADom, $dom, $setsA);

    if (empty($setsA[0])) {
        $isVariety = true;

        $setsLiDom = "/<li\s*title='(.*?)' class='w-newfigure w-newfigure-180x153(.*?)><a href='(.*?)'/";
        preg_match_all($setsLiDom, $dom, $setsLi);

        $offset = array_sea($setsLi[3][0], $setsLi[3], 1);

        for ($i = $offset; $i < count($setsLi[3]); $i++) {
            $sets[$setsLi[1][$i]] = $setsLi[3][$i];
        }
    } else {
        $isTeleplay = $isAnime = true;

        $offset = array_sea($setsA[3][0], $setsA[3], 1);
        if ($offset) {
            $sets = [];
            for ($i = $offset; $i < count($setsA[3]); $i++) {
                array_push($sets, $setsA[3][$i]);
            }
        } else {
            $sets = $setsA[3];
        }
    }
} else {
    $isMovie = true;

    $sets = $link[3];
    $default_link = $sets[0];
}

$default_link = empty($isVariety) ? $sets[0] : array_values($sets)[0];

// 可以设置偏移量的数组查询函数
function array_sea($needle, array $haystack, $offset = 0)
{
    for ($i = $offset; $i < count($haystack); $i++) {
        if ($needle == $haystack[$i]) {
            return $i;
        }
    }
    return false;
}

$keywords = '影视爬虫,' . $name . '免费在线播放,' . $name . '免费播放,' . $name . '在线播放,' . $name . '未删减版,' . $name . '下载,' . $name . '百度云';
$intro_desc = str_replace("\n", '', $intro);
$description = mb_strlen($intro_desc) > 140 ? '《' . $name . '》剧情简介：' . mb_substr($intro_desc, 0, 140) . '...' : ($intro_desc == '暂无' ? $keywords : '《' . $name . '》剧情简介：' . $intro_desc);
if (empty($album[2][0])) {
    $og_img = '';
} else {
    $og_img = $album[2][0];
}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="keywords" content="<?php echo $keywords; ?>">
    <meta name="description" content="<?php echo $description; ?>">
    <meta property="og:image" content="<?php echo $og_img; ?>">
    <title>《<?php echo $name; ?>》免费在线播放 - 影视爬虫</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link type="text/css" rel="stylesheet" href="css/common.css">
    <link type="text/css" rel="stylesheet" href="css/play.css">
</head>
<body>
<header>
    <img src='img/logo.png' alt='logo' class="tiktok">
    <ul id="menu">
        <!--菜单-->
        <?php echo Common::$menu; ?>
        <!--搜索框-->
        <?php echo Common::$search_box; ?>
    </ul>
</header>
<?php
echo "<p class='ad'>" . Common::$ad . Common::visits() . "</p>";
echo Common::inform();
?>

<div class="container">
    <div id='parsers'></div>
    <?php
    if (empty($isMovie)) {
        if (empty($sets)) {
            echo "<h3>《" . $name . "》<span style='font-size: 15px'>暂无播放资源，请稍后再来~</span></h3>";
        } else {
            echo "<h3>《" . $name . "》—— 总" . count($sets) . "集</h3><ul>";

            // 显示剧集
            $num = 0;
            foreach ($sets as $key => $val) {
                if (!empty($isVariety)) {
                    echo "<li><a class='videoA' onclick='playUrl(\"{$val}\", \"{$num}\")'>{$key}</a></li>";
                    $num++;
                } else {
                    $num = $key + 1;
                    echo "<li><a class='videoA' onclick='playUrl(\"{$val}\", \"{$key}\")'>第{$num}集</a></li>";
                }
            }
            echo '</ul><div style="clear: both;padding-top: .2pc"></div>';
        }
    } else {
        echo "<h3>《" . $name . "》<span style='font-size: 15px'>点击选择源后即可播放</span>";
        foreach ($sets as $key => $val) {
            $num = $key + 1;
            echo "<a class='videoA' onclick='playUrl(\"{$val}\", \"{$key}\")'>{$num}号源</a>";
        }
        echo "</h3>";
    }

    // 记录进点击量，无论是否可以解析
    if ($name != '啊哦，外星人来袭，页面找不到了...') {
        Spider::clickRec('click', $name);
    } else {
        file_put_contents('Cinema/lost_res.txt', $player . '-' . date('y/m H:i:s', time()) . '-' . $_SERVER['REMOTE_ADDR'] . "\n", FILE_APPEND);
    }
    ?>

    <div class="player">
        <iframe onload="iFrameResize()" allowtransparency="true" allowfullscreen="allowfullscreen" id="video"
                src="other/loading.php"></iframe>
        <script type="text/javascript">
            var videoFrame = document.getElementById('video');  // 全局使用
            var videoLink = '<?php echo $default_link; ?>';
            var sets = document.getElementsByClassName('videoA');

            function iFrameResize() {
                videoFrame.height = Math.floor(videoFrame.scrollWidth / 16 * 9);
            }
        </script>
    </div>
    <script type="text/javascript">
        // 播放器列表
        var res = ['https://jiexi.380k.com/?url=', 'https://660e.com/?url=', 'https://jx.lache.me/cc/?url='];
        if ((/(iPhone|iPad|iPod|iOS|Android)/i.test(navigator.userAgent))) {
            var buf = res[0];
            res[0] = res[2];
            res[2] = buf;
        }

        showParser();

        function showParser() {
            var parser_id = getCookie('parser');
            var parsers = document.getElementById('parsers');

            var parse_btn = "<span style='font-size: 15px;font-weight: bold'>播放器：</span>";
            for (var i = 1; i <= res.length; i++) {
                if (parser_id === i.toString()) {
                    parse_btn += "<a class='active' onclick='vParser(" + i + ")'>播放器" + i + "</a>";
                } else {
                    parse_btn += "<a onclick='vParser(" + i + ")'>播放器" + i + "</a>";
                }
            }
            parsers.innerHTML = parse_btn;
        }

        function recover() {
            var info = getCookie('<?php echo $player; ?>');

            if (info !== null) {
                info = JSON.parse(info);

                sets[info['episode']].setAttribute('id', 'cookie');
                sets[info['episode']].setAttribute('onmouseout', 'remove_hover()');
                sets[info['episode']].innerHTML = sets[info['episode']].innerText + '<span id="tooltip" class="hover">上次观看到这里</span>';

                var msg = '记忆您上次看到第 ' + (parseInt(info['episode']) + 1) + ' 集';

                var isMovie = <?php echo empty($isMovie) ? 0 : 1; ?>;
                if (isMovie) {
                    msg = '记忆您上次使用 ' + (parseInt(info['episode']) + 1) + '号源 播放';
                }

                tip(msg, "35%", 3000, "1", false);

                videoLink = info['link'];
            }
        }

        function remove_hover() {
            var tooltip_class = document.getElementById('tooltip');
            if (tooltip_class !== null) {
                tooltip_class.classList.remove('hover');
            }
        }

        function vParser(parser_id) {
            // 使用默认播放器解释时从cookie读取播放地址，若为空则使用1号播放器；
            // 若指定了播放器则使用对应播放器播放，并更新cookie
            if (parser_id === undefined) {
                parser_id = getCookie('parser');
                if (parser_id === null) {
                    parser_id = 1;
                    setCookie('parser', parser_id);
                }
            } else {
                // 保存当前使用的播放器
                setCookie('parser', parser_id);
            }

            showParser();

            videoFrame.src = res[parseInt(parser_id) - 1] + videoLink;
            console.log('Parser: ' + parser_id + ' URL: ' + videoFrame.src);
            tip("正在加载视频~", "50%", 3000, "1", false);
        }

        function playUrl(sourceUrl, i) {
            remove_hover();

            var info = {'link': sourceUrl, 'episode': i};
            setCookie('<?php echo $player; ?>', JSON.stringify(info));

            for (var j = 0; j < sets.length; j++) {
                sets[j].removeAttribute('id');
            }
            sets[i].setAttribute('id', 'cookie');

            videoLink = sourceUrl;

            // 使用默认播放器
            vParser();
        }

        var title = document.title;
        title = title.substr(1, title.length - 15) + window.location.href;

        var title_obj = JSON.parse(getCookie('play-history'));

        if (title_obj === null) {
            title_obj = [title]
        } else {
            var pos = title_obj.indexOf(title);
            // 若pos不等于-1则说明当前影视名称已经被保存
            if (pos !== -1) {
                // 删除已保存的当前影视名称
                title_obj.splice(pos, 1);
            } else if (title_obj.length > 9) {
                // 若当前影视名称未保存，则数组长度才有可能超过10，所以在此处对数组长度进行检查，删除超出10的数组元素
                title_obj.splice(9, title_obj.length - 9);
            }

            // 添加当前播放的影视名称
            title_obj.unshift(title);
        }
        // 保存播放记录信息到cookie，时长为7天
        setCookie('play-history', JSON.stringify(title_obj));

        function setCookie(cookieKey, cookieValue) {
            var expireDays = 7;
            var expDate = new Date();
            expDate.setDate(expDate.getDate() + expireDays);
            // noinspection JSDeprecatedSymbols
            document.cookie = cookieKey + "=" + escape(cookieValue) + ("; expires=" + expDate.toGMTString());
        }

        function getCookie(cookieKey) {
            var arr, reg = new RegExp("(^| )" + cookieKey + "=([^;]*)(;|$)");
            // noinspection JSDeprecatedSymbols
            return (arr = document.cookie.match(reg)) ? unescape(arr[2]) : null;
        }
    </script>
    <h3 style="margin-top: -10px">剧情简介：</h3>
    <p style="margin-top: -15px;line-height: 25px">　　<?php echo str_replace("\n", '<br>　　', $intro); ?></p>

    <iframe style="background-color: transparent;padding: unset;height: 555px;margin: -10px 0;" src=<?php
    $s = strpos($play, '/', 1) + 1;
    $u = substr($play, $s, strpos($play, '.html') - $s);
    echo "https://www.lifanko.cn/chat/index.php?u=" . $u . '&n=' . $name;
    ?>></iframe>
</div>
<div id="qus">
    <span class="float_btn">无法<br>播放</span>
    <div id="que-tip">视频无法播放的解决方法：<br>&nbsp;&nbsp;&nbsp;※ 切换上方的 播放器1 或 播放器2 或 播放器3<br>三个播放器均无法播放
        或 点击按钮无反应：<br>&nbsp;&nbsp;&nbsp;※ 发送邮件：lzw@163.com 或 添加微信：lifanko
    </div>
</div>
<?php echo Common::$history; ?>
<footer>
    <?php
    echo Common::$tip;
    echo Common::$footer;
    ?>
</footer>
<script type="text/javascript" src="https://cdn.lifanko.cn/js/tip.min.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript">
    Object.defineProperty(navigator, "userAgent", {
        value: "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36",
        writable: false
    });

    window.onresize = function () {
        autoSize();
        // Fixed player size: 16-9
        iFrameResize();
    };

    // 显示播放历史
    setTimeout(function () {
        recover();
    }, 50);
</script>
<script type="text/javascript" src="js/tip.js"></script>
</body>
</html>