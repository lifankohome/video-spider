/**
 * Created by lifanko  lee on 2017/12/13.
 */
tip("欢迎使用影视爬虫！", "12%", 3000, "1", false);

//在窗口调整时图片大小自动适应
var img = document.getElementsByClassName('img');
autoSize(img);  //初始化

window.onresize = function () { //监听
    autoSize(img);
};

function autoSize(img) {
    //仅当有资源时才重新调整大小
    if (img.length) {
        var height = (img[0].width * 1.4).toFixed(0);   //取宽度
        for (var i = 0; i < img.length; i++) {  //根据比例统一高度
            img[i].style.height = height + 'px'
        }
    }

    //自动调整搜索框大小
    var win_width = document.body.clientWidth - 1050;

    if (win_width) {
        if (win_width > 125) {
            win_width = 125;
        }
        document.getElementById("searchBox").style.width = win_width + 175 + 'px';
    }
}

//搜索功能
var search = document.getElementById('searchBox');
var searchText = document.getElementById('searchText');

search.onkeyup = function () {
    if (search.value) {
        searchText.innerHTML = "<a href='search.php?kw=" + search.value + "' style='background-color: #444;color: white;margin-right: -1pc;border-top-right-radius: 5px;border-bottom-right-radius: 5px'>搜索</a>";
    } else {
        searchText.innerHTML = "<img src='img/yspc.png' style='margin: 0;height: 26px;position: relative;top: 7px'>";
    }
};

//回车搜索
document.onkeydown = function (e) {
    var theEvent = window.event || e;
    var code = theEvent.keyCode || theEvent.which;

    if (code === 13) {
        if (search.value) {
            window.location.href = "search.php?kw=" + search.value;
            tip("正在搜索：" + search.value, "12%", 2000, "1", true);
        } else {
            window.location.href = "search.php";
            tip("正在搜索最热视频", "12%", 2000, "1", true);
        }
    }
};

// 播放历史显示控制
var his_frame = document.getElementById("fra-history");
function showHistory() {
    his_frame.style.right = "0px";
}
function hideHistory() {
    his_frame.style.right = -300 + "px";
}

//百度统计
var _hmt = _hmt || [];
(function () {
    var hm = document.createElement("script");
    hm.src = "https://hm.baidu.com/hm.js?a258eee7e1b38615e85fde12692f95cc";
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(hm, s);
})();