/**
 * Created by lifanko  lee on 2017/12/13.
 */
//在窗口调整时图片大小自动适应
var img = document.getElementsByClassName('img');
autoSize(img);  //初始化

window.onresize = function () { //监听
    autoSize(img)
};

function autoSize(img) {
    var height = (img[0].width * 1.4).toFixed(0);   //取宽度
    for (var i = 0; i < img.length; i++) {  //根据比例统一高度
        img[i].style.height = height + 'px'
    }
}

//搜索功能
var search = document.getElementById('searchBox');
var searchText = document.getElementById('searchText');

search.onkeyup = function () {
    if (search.value) {
        searchText.innerHTML = "<a href='search.php?kw=" + search.value + "' style='background-color: #444;margin-right: -1pc'>搜索</a>";
    } else {
        searchText.innerText = '影视爬虫';
    }
};

//百度统计
var _hmt = _hmt || [];
(function() {
    var hm = document.createElement("script");
    hm.src = "https://hm.baidu.com/hm.js?a258eee7e1b38615e85fde12692f95cc";
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(hm, s);
})();