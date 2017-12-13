/**
 * Created by lifanko  lee on 2017/12/13.
 */
//用于在窗口调整时图片大小自动适应
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