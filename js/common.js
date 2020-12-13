// 搜索功能
var searchBox = document.getElementById('searchBox');
var searchText = document.getElementById('searchText');

var holder_timer;
var holder_list = document.getElementById("holder_list");

// 初始化
autoSize();

function autoSize() {
    // 自动调整搜索和推荐框大小
    var win_width = document.body.clientWidth - 1030;

    if (win_width) {
        if (win_width > 125) {
            win_width = 125;
        }
        document.getElementById("searchBox").style.width = win_width + 175 + 'px';
        document.getElementById("holder").style.width = win_width + 197 + 'px';
    }
}

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
                        var kw = ret[i].replace(/(<([^>]+)>)/g, '');
                        holder_list_html += "<li title='点击搜索《" + kw + "》' onclick='holder_up(\"" + kw + "\")'>" + ret[i] + "</li>";
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

    window.location.href = 'search.php?kw=' + searchBox.value;
    tip('正在搜索：《' + searchBox.value + '》', "12%", 5000, "1", true);
    holder_list.style.display = 'none';
}

// 回车搜索
document.onkeydown = function (e) {
    var theEvent = window["event"] || e;
    if (theEvent["keyCode"] === 13) {
        if (searchBox.value) {
            window.location.href = "search.php?kw=" + searchBox.value;
            tip("正在搜索：" + searchBox.value, "12%", 2000, "1", true);
        } else {
            window.location.href = "search.php";
            tip("正在搜索最热视频", "12%", 2000, "1", true);
        }
    }
};

// 打开反馈页面
document.getElementById('feedback_btn').onclick = function () {
    document.getElementById('f-box').style.display = 'block';
}

// 百度统计
var _hmt = _hmt || [];
(function () {
    var hm = document.createElement("script");
    hm.src = "https://hm.baidu.com/hm.js?a258eee7e1b38615e85fde12692f95cc";
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(hm, s);
})();

console.log("你知道吗？《影视爬虫》为开源程序，于2017年12月6日开始编写并不断维护更新，至今已成长为一个稳定可靠的视频播放网站！\n开源地址：https://github.com/lifankohome/video-spider \n\n欢迎使用本开源代码建造属于自己的视频网站，任何人均可无限制地传播和使用本程序，但您需要在您的网站添加友情链接并告知lifankohome@163.com，否则，《影视爬虫》将通过合法手段撤回您对源代码的使用权。");
