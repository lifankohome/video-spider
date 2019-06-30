/**
 * Created by zw on 2019/1/9.
 */
function setCookie(cookieKey, cookieValue, expireDays) {
    var expDate = new Date();
    expDate.setDate(expDate.getDate() + expireDays);
    //noinspection JSDeprecatedSymbols
    document.cookie = cookieKey + "=" + escape(cookieValue) +
        ((expireDays == null) ? "" : "; expires=" + expDate.toGMTString());
}

function getCookie(cookieKey) {
    var arr, reg = new RegExp("(^| )" + cookieKey + "=([^;]*)(;|$)");
    //noinspection JSDeprecatedSymbols
    return (arr = document.cookie.match(reg)) ? unescape(arr[2]) : null;
}

// 获取长度为len的随机字符串
function getRandomString(len) {
    len = len || 32;
    var $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'; // 默认去掉了容易混淆的字符oOLl,9gq,Vv,Uu,I1
    var maxPos = $chars.length;
    var pwd = '';
    for (i = 0; i < len; i++) {
        pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
    }
    return pwd;
}

if (window.location.protocol === 'http:') {
    var sub;

    if (getCookie('sCount')) {
        sub = getCookie('sCount');
    } else {
        sub = '/sCount/video/' + getRandomString(64);
        setCookie("sCount", sub, 1);
    }

    var client = mqtt.connect('ws://ali.lifanko.cn:8083/mqtt', {username: 'hpu-iot', password: '1420mqtt'});
    client.publish(sub, "sCount-pre-video");

    // 每分钟更新一次在线信息
    client.publish(sub, "sCount-add-video");
    setInterval(function () {
        client.publish(sub, "sCount-add-video");
    }, 60000);

    client.subscribe('/sCount/inform/video');
    client.on("message", function (topic, payload) {
        document.getElementById('sCount').innerText = '【' + (parseInt(payload.toString().substring(14)) + 1).toString() + '人在线】';
    });
}

