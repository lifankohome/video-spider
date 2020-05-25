/**
 * Created by lifanko  lee on 2017/12/13.
 */
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
            img[i].style.height = height + 'px';
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
var searchBox = document.getElementById('searchBox');
var searchText = document.getElementById('searchText');

searchBox.onkeyup = function () {
    if (searchBox.value) {
        searchText.innerHTML = "<a onclick='s();' style='cursor: pointer;background-color: #444;color: white;margin-right: -1pc;border-top-right-radius: 5px;border-bottom-right-radius: 5px'>搜索</a>";
    } else {
        searchText.innerHTML = "<img src='img/yspc.png' alt='tip'>";
    }
};

//回车搜索
document.onkeydown = function (e) {
    var theEvent = window.event || e;
    var code = theEvent.keyCode || theEvent.which;

    if (code === 13) {
        search();
    }
};

function s() {search();}

function search() {
    if (searchBox.value) {
        window.location.href = "search.php?kw=" + searchBox.value;
        tip("正在搜索：" + searchBox.value, "12%", 2000, "1", true);
    } else {
        window.location.href = "search.php";
        tip("正在搜索最热视频", "12%", 2000, "1", true);
    }
}

// 播放历史显示控制
var his_frame = document.getElementById("fra-history");

function showHistory() {
    his_frame.style.right = "0px";
}

function hideHistory() {
    his_frame.style.right = -300 + "px";
}

// 当元素存在时轮播图片
var ele = document.getElementsByClassName('js-slide-img');
var nav_index = document.getElementsByClassName('nav_index');
var index = 0;
if (ele.length > 0) {
    var nav = document.getElementById('nav');
    var nav_buffer = '';
    for (var n = 0; n < ele.length; n++) {
        nav_buffer += "<li class='nav_index' onclick='showIndex(" + n + ")'>" + (n + 1) + "</li>";
    }
    nav.innerHTML = nav_buffer;

    showIndex(index);

    setInterval(function () {
        showIndex(index);

        if (++index === ele.length) {
            index = 0;
        }
    }, 3500);
}

function showIndex(i, reset) {
    for (var j = 0; j < ele.length; j++) {
        if (j === i) {
            index = j;
            ele[j].setAttribute('class', 'b-topslidernew-img js-slide-img active');
            nav_index[j].setAttribute('class', 'nav_index active');
        } else {
            ele[j].setAttribute('class', 'b-topslidernew-img js-slide-img');
            nav_index[j].setAttribute('class', 'nav_index');
        }
    }
}

//百度统计
var _hmt = _hmt || [];
(function () {
    var hm = document.createElement("script");
    hm.src = "https://hm.baidu.com/hm.js?a258eee7e1b38615e85fde12692f95cc";
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(hm, s);
})();

console.log("你知道吗？《影视爬虫》为开源程序，于2017年12月6日开始编写并不断维护更新，至今已成长为一个稳定可靠的视频播放网站！\n开源地址：https://github.com/lifankohome/video-spider \n\n欢迎使用本开源代码建造属于自己的视频网站，任何人均可无限制地传播和使用本程序，但您需要在您的网站添加友情链接并告知lifankohome@163.com，否则，《影视爬虫》将通过合法手段撤回您对源代码的使用权。");