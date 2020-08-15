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
        $player = 'https://www.360kan.com' . $play;
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

        $setsLiDom = "/<li\s*title='(.*?)' class='w-newfigure w-newfigure-180x153\s*'><a href='(.*?)'/";
        preg_match_all($setsLiDom, $dom, $setsLi);

        $offset = array_sea($setsLi[2][0], $setsLi[2], 1);

        for ($i = $offset; $i < count($setsLi[2]); $i++) {
            $sets[$setsLi[1][$i]] = $setsLi[2][$i];
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
    <ul>
        <li><a href='hot.php'>首页</a></li>
        <li><a href='index.php'>电影</a></li>
        <li><a href='variety.php'>综艺</a></li>
        <li><a href='teleplay.php'>电视剧</a></li>
        <li><a href='anime.php'>动漫</a></li>
        <li><a href='other/about.html'>说明</a></li>
        <li style="background-color: rgba(255,0,0,0.5);color: white;line-height: 45px;padding-top: 10px;margin-left: 10px">
            <a href='http://finance.lifanko.cn' target="_blank"
               style="font-family: '华文仿宋',sans-serif;color: yellow;text-decoration: none;font-size: 20px;line-height: 25px;">助力理财<br>晫重财经</a>
        </li>
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
    Spider::clickRec('click', $name);
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

    <iframe style="background-color: transparent;padding: unset;height: 555px;margin: -10px 0;"
            src=<?php $u = substr($play, strpos($play, '/', 1) + 1, -5);
    echo "https://www.lifanko.cn/chat/index.php?u=" . $u . '&n=' . $name; ?>></iframe>
</div>
<div id="qus">
    <span id="qus-btn">无法<br>播放</span>
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
<script type="text/javascript">
    Object.defineProperty(navigator, "userAgent", {
        value: "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36",
        writable: false
    });

    // 搜索功能
    var searchBox = document.getElementById('searchBox');
    var searchText = document.getElementById('searchText');

    var holder_timer;
    var holder_list = document.getElementById("holder_list");

    function holder() {
        if (searchBox.value) {
            searchText.innerHTML = "<a href='search.php?kw=" + searchBox.value + "' style='background-color: #444;color: white;margin-right: -1pc;border-top-right-radius: 5px;border-bottom-right-radius: 5px'>搜索</a>";

            holder_list.style.display = 'block';

            clearTimeout(holder_timer);
            holder_timer = setTimeout(function () {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'holder.php?kw=' + searchBox.value, true);
                xhr.onload = function () {
                    var holder_list = document.getElementById("holder_list");
                    var ret = JSON.parse(this.responseText)

                    var holder_list_html = '';
                    if (ret.length) {
                        for (var i = 0; i < ret.length; i++) {
                            var kw = ret[i].replace('<b>', '');
                            kw = kw.replace('</b>', '')
                            holder_list_html += "<li title='点击将《" + kw + "》填充进搜索框' onclick='holder_up(\"" + kw + "\")'>" + ret[i] + "</li>";
                        }
                    } else {
                        holder_list_html = "<li style='font-size: 12px;text-align: center'>无搜索推荐</li>";
                    }

                    holder_list.innerHTML = holder_list_html;
                }
                xhr.send();
            }, 500)
        } else {
            searchText.innerHTML = "<img src='img/yspc.png' alt='tip'>";

            holder_list.style.display = 'none';
        }
    }

    function holder_up(kw) {
        searchBox.value = kw;
        searchText.innerHTML = "<a href='search.php?kw=" + searchBox.value + "' style='background-color: #444;color: white;margin-right: -1pc;border-top-right-radius: 5px;border-bottom-right-radius: 5px'>搜索</a>";
    }

    // 回车搜索
    document.onkeydown = function (e) {
        var theEvent = window.event || e;
        var code = theEvent.keyCode || theEvent.which;
        if (code === 13) {
            if (searchBox.value) {
                window.location.href = "search.php?kw=" + searchBox.value;
                tip("正在搜索：" + searchBox.value, "12%", 2000, "1", true);
            } else {
                window.location.href = "search.php";
                tip("正在搜索最热视频", "12%", 2000, "1", true);
            }
        }
    };

    function autoSize(img) {
        // 仅当有资源时才重新调整大小
        if (img.length) {
            // 根据宽度计算高度，比例 5:7
            var height = (img[0].width * 1.4).toFixed(0);
            for (var i = 0; i < img.length; i++) {
                img[i].style.height = height + 'px'
            }
        }

        // 自动调整搜索框大小
        var win_width = document.body.clientWidth - 1050;
        if (win_width) {
            if (win_width > 125) {
                win_width = 125;
            }
            document.getElementById("searchBox").style.width = win_width + 165 + 'px';
            document.getElementById("holder").style.width = win_width + 187 + 'px';
        }
    }

    // 初始化
    autoSize([]);

    // 显示播放历史
    setTimeout(function () {
        recover();
    }, 50);

    window.onresize = function () {
        autoSize([]);
        // Fixed player size: 16-9
        iFrameResize();
    };

    // 百度统计
    var _hmt = _hmt || [];
    (function () {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?a258eee7e1b38615e85fde12692f95cc";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();

    console.log("你知道吗？《影视爬虫》为开源程序，于2017年12月6日开始编写并不断维护更新，至今已成长为一个稳定可靠的视频播放网站！\n开源地址：https://github.com/lifankohome/video-spider \n\n欢迎使用本开源代码建造属于自己的视频网站，任何人均可无限制地传播和使用本程序，但您需要在您的网站添加友情链接并告知lifankohome@163.com，否则，《影视爬虫》将通过合法手段撤回您对源代码的使用权。");
</script>
<script type="text/javascript" src="js/tip.js"></script>
</body>
</html>