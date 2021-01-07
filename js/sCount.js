/**
 * Created by lifanko lee on 2019/1/9.
 */
function setCookie(cookieKey, cookieValue, expireDays) {
    let expDate = new Date();
    expDate.setDate(expDate.getDate() + expireDays);
    //noinspection JSDeprecatedSymbols
    document.cookie = cookieKey + "=" + escape(cookieValue) +
        ((expireDays == null) ? "" : "; expires=" + expDate.toGMTString());
}

function getCookie(cookieKey) {
    let arr, reg = new RegExp("(^| )" + cookieKey + "=([^;]*)(;|$)");
    //noinspection JSDeprecatedSymbols
    return (arr = document.cookie.match(reg)) ? unescape(arr[2]) : null;
}

function getRandomString(len) {
    len = len || 32;
    let $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'; // 默认去掉了容易混淆的字符oOLl,9gq,Vv,Uu,I1
    let maxPos = $chars.length;
    let pwd = '';
    for (i = 0; i < len; i++) {
        pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
    }
    return pwd;
}

let sub = null;

if (getCookie('sCount')) {
    sub = getCookie('sCount');
} else {
    sub = '/sCount/video/' + getRandomString(32);
    setCookie("sCount", sub, 7);
}

const client = mqtt.connect('wss://www.lifanko.cn:8084/mqtt', {username: 'hpu-iot', password: '1420mqtt'});

add_num()

// update every minute
setInterval(function () {
    add_num()
}, 60000);

function add_num() {
    client.publish(sub, "sCount-add-video");

    setTimeout(function () {
        client.publish(sub, "sCount-pre-video");
    }, 1000)
}

let sCnt = document.getElementById('sCnt');

client.subscribe('/sCount/inform/video');
client.on("message", function (topic, payload) {
    let sCount = JSON.parse(payload.toString())
    sCnt.innerText = '，实时:' + (sCount.total * 5)
});
