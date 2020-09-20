var url = window.location.pathname;

var menu = document.getElementById('menu');
var li = menu.getElementsByTagName('li');

switch (url.substring(url.lastIndexOf('/'), url.length)) {
    case '/hot.php':
        tip("欢迎使用影视爬虫~~", "15%", 3000, "1", false);
        li[0].setAttribute("class", "active");
        break;
    case '/':
    case '/index.php':
        tip("影视爬虫 - 电影频道", "15%", 3000, "1", false);
        li[1].setAttribute("class", "active");
        break;
    case '/variety.php':
        tip("影视爬虫 - 综艺频道", "15%", 3000, "1", false);
        li[2].setAttribute("class", "active");
        break;
    case '/teleplay.php':
        tip("影视爬虫 - 电视剧频道", "15%", 3000, "1", false);
        li[3].setAttribute("class", "active");
        break;
    case '/anime.php':
        tip("影视爬虫 - 动漫频道", "15%", 3000, "1", false);
        li[4].setAttribute("class", "active");
        break;
    default:
        tip("影视爬虫 - yspc.vip", "15%", 3000, "1", false);
        break;
}
