# video-spider
An easy video-spider that Using PHP

### Demo

http://ali.lifanko.cn/video/

### Screenshot

![Screenshot](https://github.com/lifankohome/video-spider/blob/master/screenshot.jpg?raw=true)

### Feature

 + 无须数据库
 + 任意级目录配置
 + 全自动爬取&更新
 + 播放剧集记忆（cookie:24h）
 + 无注册/无登录/无广告

### Linux Server

PHP7 + Apache/2.4.6 (CentOS)

##### Setup
将文件复制进应用目录，默认文档设为 'index.php' 即可。

##### Attention
无法在Linux服务器上实现命名空间类的自动加载，请在文件中引入所需类（根据use到的类进行）：
```
include_once('Cinema/Spider.php');
include_once('Cinema/Common.php');
```
移除类自动加载：
```
/**
 * 类自动加载
 * @param $class
 */
function __autoload($class)
{
    $file = $class . '.php';
    if (is_file($file)) {
        /** @noinspection PhpIncludeInspection */
        require_once($file);
    }
}
```

### Windows Server

PHP5.6.28 + IIS6.2 (Windows Server 2012)

##### Setup:
将文件复制进应用目录，默认文档设为 'index.php' 即可。

### MIT License
Copyright (c) 2017 lifanko lee