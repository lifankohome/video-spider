<?php
/**
 * Created by PhpStorm.
 * User: lifanko  lee
 * Date: 2017/12/6
 * Time: 12:37
 */

use Cinema\Common;
use Cinema\Spider;

include('Cinema/Spider.php');
include('Cinema/Common.php');

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
    $player = substr($player, 0, strpos($player, '.html') + 5);
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
echo Common::background();
echo "<p class='ad'>" . Common::$ad . Common::visits() . "</p>";
echo Common::inform();
?>

<div class="container">
    <div id='parsers'></div>
    <?php
    if ($name == '啊哦，外星人来袭，页面找不到了...') {
        file_put_contents('Cinema/lost_res.txt', $player . ' - ' . date('y/m/d H:i:s', time()) . ' - ' . $_SERVER['REMOTE_ADDR'] . "\n", FILE_APPEND);
        $name = '视频暂时无法播放，请稍后再来';
    } else {
        Spider::clickRec('click', $name);
    }

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
    ?>

    <div class="player">
        <iframe onload="iFrameResize()" allowtransparency="true" allowfullscreen="allowfullscreen" id="video"
                src="other/loading.php"></iframe>
        <script type="text/javascript">
            let videoFrame = document.getElementById('video');  // 全局使用
            let videoLink = '<?php echo $default_link; ?>';
            let sets = document.getElementsByClassName('videoA');

            function iFrameResize() {
                videoFrame.height = Math.floor(videoFrame.scrollWidth / 16 * 9);
            }
        </script>
    </div>
    <script type="text/javascript">
        // 播放器列表
        let res = ['https://jiexi.380k.com/?url=', 'https://660e.com/?url='];

        showParser();

        function showParser() {
            let parser_id = getCookie('parser');
            let parsers = document.getElementById('parsers');

            let parse_btn = "<span style='font-size: 15px;font-weight: bold'>播放器：</span>";
            for (let i = 1; i <= res.length; i++) {
                if (parser_id === i.toString()) {
                    parse_btn += "<a class='active' onclick='vParser(" + i + ")'>播放器" + i + "</a>";
                } else {
                    parse_btn += "<a onclick='vParser(" + i + ")'>播放器" + i + "</a>";
                }
            }
            parsers.innerHTML = parse_btn;
        }

        function recover() {
            let info = getCookie('<?php echo $player; ?>');

            if (info !== null) {
                info = JSON.parse(info);

                sets[info['episode']].setAttribute('id', 'cookie');
                sets[info['episode']].setAttribute('onmouseout', 'remove_hover()');
                sets[info['episode']].innerHTML = sets[info['episode']].innerText + '<span id="tooltip" class="hover">上次观看到这里</span>';

                let msg = '记忆您上次看到第 ' + (parseInt(info['episode']) + 1) + ' 集';

                let isMovie = <?php echo empty($isMovie) ? 0 : 1; ?>;
                if (isMovie) {
                    msg = '记忆您上次使用 ' + (parseInt(info['episode']) + 1) + '号源 播放';
                }

                tip(msg, "35%", 3000, "1", false);

                videoLink = info['link'];
            }
        }

        function remove_hover() {
            let tooltip_class = document.getElementById('tooltip');
            if (tooltip_class !== null) {
                tooltip_class.classList.remove('hover');
            }
        }

        function vParser(parser_id) {
            // 使用默认播放器解释时从cookie读取播放地址，若为空则使用1号播放器；
            // 若指定了播放器则使用对应播放器播放，并更新cookie
            if (parser_id === undefined) {
                parser_id = getCookie('parser');
                if (parser_id === null || parser_id > res.length) {
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

            let info = {'link': sourceUrl, 'episode': i};
            setCookie('<?php echo $player; ?>', JSON.stringify(info));

            for (let j = 0; j < sets.length; j++) {
                sets[j].removeAttribute('id');
            }
            sets[i].setAttribute('id', 'cookie');

            videoLink = sourceUrl;

            // 使用默认播放器
            vParser();
        }

        let title = document.title;
        title = title.substr(1, title.length - 15) + window.location.href;

        let title_obj = JSON.parse(getCookie('play-history'));

        if (title_obj === null) {
            title_obj = [title]
        } else {
            let pos = title_obj.indexOf(title);
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
            let expireDays = 7;
            let expDate = new Date();
            expDate.setDate(expDate.getDate() + expireDays);
            // noinspection JSDeprecatedSymbols
            document.cookie = cookieKey + "=" + escape(cookieValue) + ("; expires=" + expDate.toGMTString());
        }

        function getCookie(cookieKey) {
            let arr, reg = new RegExp("(^| )" + cookieKey + "=([^;]*)(;|$)");
            // noinspection JSDeprecatedSymbols
            return (arr = document.cookie.match(reg)) ? unescape(arr[2]) : null;
        }
    </script>

    <div class="box intro">
        <div class="head">剧情简介：</div>
        <p>　　<?php echo str_replace("\n", '<br>　　', $intro); ?></p>
    </div>

    <iframe class="chat" src=<?php $s = strpos($play, '/', 1) + 1;
    $u = substr($play, $s, strpos($play, '.html') - $s);
    echo "https://www.lifanko.cn/chat/index.php?u=" . $u . '&n=' . $name;
    ?>></iframe>
</div>
<!--播放历史-->
<?php echo Common::$history; ?>
<!--问题反馈-->
<?php echo Common::$feedback; ?>
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