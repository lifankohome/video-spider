var url = window.location.pathname;

switch (url.substring(url.lastIndexOf('/'), url.length)) {
    case '/':
    case '/index.php':
        tip("影视爬虫 - 电影频道", "15%", 3000, "1", false);
        break;
    case '/hot.php':
        tip("欢迎使用影视爬虫~~", "15%", 3000, "1", false);
        break;
    case '/variety.php':
        tip("影视爬虫 - 综艺频道", "15%", 3000, "1", false);
        break;
    case '/teleplay.php':
        tip("影视爬虫 - 电视剧频道", "15%", 3000, "1", false);
        break;
    case '/anime.php':
        tip("影视爬虫 - 动漫频道", "15%", 3000, "1", false);
        break;
    default:
        tip("影视爬虫 - yspc.vip", "15%", 3000, "1", false);
        break;
}
