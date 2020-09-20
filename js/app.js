/**
 * Created by lifanko  lee on 2017/12/13.
 */

window.onresize = function () { //监听
    autoSize();
};

//当元素存在时轮播图片
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

function showIndex(i) {
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
