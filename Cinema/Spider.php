<?php
/**
 * Created by PhpStorm.
 * User: lifanko  lee
 * Date: 2017/12/6
 * Time: 13:25
 */

namespace Cinema;

class Spider
{
    private static $moviesCat = array();
    private static $tvCat = array();
    private static $varietyCat = array();
    private static $presentCat = '';

    public static function getPresentCat()
    {
        return self::$presentCat;
    }

    private static function setPresentCat($presentCat)
    {
        self::$presentCat = $presentCat;
    }

    /**
     * @param string $cat
     * @param int $page
     * @return array
     */
    public static function getMovies($cat = 'all', $page = 1)
    {
        $dom = file_get_contents('https://www.360kan.com/dianying/list.php?year=all&area=all&act=all&cat=' . $cat . '&pageno=' . $page);

        $movieNameDom = '#<span class="s1">(.*?)</span>#';
        $movieScoreDom = '#<span class="s2">(.*?)</span>#';
        $movieYearDom = '#<span class="hint">(.*?)</span>#';
        $movieLinkDom = '#<a class="js-tongjic" href="(.*?)">#';
        $movieActorDom = '# <p class="star">(.*?)</p>#';
        $movieImgDom = '#<div class="cover g-playicon">
                                <img src="(.*?)">#';
        $movieCatDom = '#<a class="js-tongjip" href="https://www.360kan.com/dianying/list.php\?year=all\&area=all\&act=all\&cat=(.*?)" target="_self">(.*?)\s+(<i class="s-hot-icon"><\/i>\s+){0,1}<\/a>#';
        $presentCatDom = '#<b class="on">(.*?)</b>#';

        preg_match_all($movieNameDom, $dom, $movieName);
        preg_match_all($movieScoreDom, $dom, $movieScore);
        preg_match_all($movieYearDom, $dom, $movieYear);
        preg_match_all($movieLinkDom, $dom, $movieLink);
        preg_match_all($movieActorDom, $dom, $movieActor);
        preg_match_all($movieImgDom, $dom, $movieImg);
        preg_match_all($movieCatDom, $dom, $movieCat);
        preg_match_all($presentCatDom, $dom, $presentCat);

        self::setPresentCat($presentCat[1][0]);

        $movies = array();
        foreach ($movieName[1] as $key => $value) {
            $buffer['link'] = base64_encode('https://www.360kan.com' . $movieLink[1][$key]);
            $buffer['name'] = $movieName[1][$key];
            $buffer['score'] = $movieScore[1][$key];
            $buffer['img'] = $movieImg[1][$key];
            $buffer['year'] = $movieYear[1][$key];
            $buffer['actor'] = $movieActor[1][$key];

            $movies[$key] = $buffer;
        }

        $movieCatArr = array();
        foreach ($movieCat[1] as $key => $val) {
            $movieCatArr[$val] = $movieCat[2][$key];
        }

        //为了页面美观，仅保留显示前20个分类
        $movieCatArr = array_slice($movieCatArr, 0, 20, true);

        self::setMoviesCat($movieCatArr);

        return $movies;
    }

    public static function getMoviesCat()
    {
        return self::$moviesCat;
    }

    private static function setMoviesCat($moviesCat)
    {
        self::$moviesCat = $moviesCat;
    }

    /**
     * @param string $cat
     * @param int $page
     * @return array
     */
    public static function getTvs($cat = 'all', $page = 1)
    {
        $dom = file_get_contents('https://www.360kan.com/dianshi/list.php?year=all&area=all&act=all&cat=' . $cat . '&pageno=' . $page);

        $tvNameDom = '#<span class="s1">(.*?)</span>#';
        $tvLinkDom = '#<a class="js-tongjic" href="(.*?)">#';
        $tvActorDom = '# <p class="star">(.*?)</p>#';
        $tvImgDom = '#<div class="cover g-playicon">
                                <img src="(.*?)">#';
        $tvUpdateDom = '#<span class="hint">(.*?)</span> #';
        $tvCatDom = '#<a class="js-tongjip" href="https://www.360kan.com/dianshi/list.php\?year=all\&area\=all\&act\=all\&cat\=(.*?)" target="_self">(.*?)\s+(<i class="s-hot-icon"><\/i>\s+){0,1}<\/a>#';
        $presentCatDom = '#<b class="on">(.*?)</b>#';

        preg_match_all($tvLinkDom, $dom, $tvLink);
        preg_match_all($tvNameDom, $dom, $tvName);
        preg_match_all($tvImgDom, $dom, $tvImg);
        preg_match_all($tvActorDom, $dom, $tvActor);
        preg_match_all($tvUpdateDom, $dom, $tvUpdate);
        preg_match_all($tvCatDom, $dom, $tvCat);
        preg_match_all($presentCatDom, $dom, $presentCat);

        self::setPresentCat($presentCat[1][0]);

        $teleplays = array();
        foreach ($tvName[1] as $key => $value) {
            $buffer['link'] = base64_encode('https://www.360kan.com' . $tvLink[1][$key]);
            $buffer['name'] = $tvName[1][$key];
            $buffer['img'] = $tvImg[1][$key];
            $buffer['actor'] = $tvActor[1][$key];
            $buffer['update'] = $tvUpdate[1][$key];

            $teleplays[$key] = $buffer;
        }

        $tvCatArr = array();
        foreach ($tvCat[1] as $key => $val) {
            $tvCatArr[$val] = $tvCat[2][$key];
        }

        //为了页面美观，仅保留显示前20个分类
        $tvCatArr = array_slice($tvCatArr, 0, 20, true);

        self::setTvCat($tvCatArr);

        return $teleplays;
    }

    public static function getTvCat()
    {
        return self::$tvCat;
    }

    private static function setTvCat($tvCat)
    {
        self::$tvCat = $tvCat;
    }

    /**
     * @param string $cat
     * @param int $page
     * @return array
     */
    public static function getVarieties($cat = 'all', $page = 1)
    {
        $dom = file_get_contents('https://www.360kan.com/zongyi/list?act=all&area=all&cat=' . $cat . '&pageno=' . $page);

        $varietyNameDom = '#<span class="s1">(.*?)</span>#';
        $varietyLinkDom = '#<a class="js-tongjic" href="(.*?)">#';
        $varietyActorDom = '# <p class="star">(.*?)</p>#';
        $varietyImgDom = '#<div class="cover g-playicon">
                                <img src="(.*?)">#';
        $varietyUpdateDom = '#<span class="hint">(.*?)</span> #';
        $varietyCatDom = '#<a class="js-tongjip" href="https://www.360kan.com/zongyi/list\?act\=all\&area\=all\&cat\=(.*?)" target="_self">(.*?)\s+(<i class="s-hot-icon"><\/i>\s+){0,1}<\/a>#';
        $presentCatDom = '#<b class="on">(.*?)</b>#';

        preg_match_all($varietyLinkDom, $dom, $varietyLink);
        preg_match_all($varietyNameDom, $dom, $varietyName);
        preg_match_all($varietyImgDom, $dom, $varietyImg);
        preg_match_all($varietyActorDom, $dom, $varietyActor);
        preg_match_all($varietyUpdateDom, $dom, $varietyUpdate);
        preg_match_all($varietyCatDom, $dom, $varietyCat);
        preg_match_all($presentCatDom, $dom, $presentCat);

        self::setPresentCat($presentCat[1][0]);

        $teleplays = array();
        foreach ($varietyName[1] as $key => $value) {
            $buffer['link'] = base64_encode('https://www.360kan.com' . $varietyLink[1][$key]);
            $buffer['name'] = $varietyName[1][$key];
            $buffer['img'] = $varietyImg[1][$key];
            $buffer['actor'] = $varietyActor[1][$key];
            $buffer['update'] = $varietyUpdate[1][$key];

            $teleplays[$key] = $buffer;
        }

        $varietyCatArr = array();
        foreach ($varietyCat[1] as $key => $val) {
            //为了页面美观，仅保留分类长度为2个字的综艺
            if (mb_strlen($varietyCat[2][$key]) <= 2) {
                $varietyCatArr[$val] = $varietyCat[2][$key];
            }
        }

        self::setVarietyCat($varietyCatArr);

        return $teleplays;
    }

    public static function getVarietyCat()
    {
        return self::$varietyCat;
    }

    private static function setVarietyCat($varietyCat)
    {
        self::$varietyCat = $varietyCat;
    }

    /**
     * @param $kw
     * @return array
     */
    public static function search($kw)
    {
        if (empty($kw)) {
            $dom = file_get_contents('http://www.360kan.com/dianying/list.php?year=all&area=all&act=all&cat=all&pageno=all');
        } else {
            $dom = file_get_contents('http://so.360kan.com/index.php?kw=' . $kw);
        }

        $nameDom = '#js-playicon" title="(.*?)"\s*data#';
        $linkDom = '#a href="(.*?)" class="g-playicon js-playicon"#';
        $imgDom = '#<img src="(.*?)" alt="(.*?)" \/>[\s\S]+?</a>#';
        $typeDom = '#<span class="playtype">(.*?)<\/span>#';

        preg_match_all($nameDom, $dom, $name);
        preg_match_all($linkDom, $dom, $link);
        preg_match_all($imgDom, $dom, $img);
        preg_match_all($typeDom, $dom, $type);

        $search = array();
        foreach ($name[1] as $key => $value) {
            $buffer['name'] = $name[1][$key];

            if (isset($img[1][$key])) {
                $buffer['img'] = $img[1][$key];
            } else {
                $buffer['img'] = 'img/noCover.jpg';
            }

            if (isset($type[1][$key])) {
                $buffer['type'] = $type[1][$key];
            } else {
                $buffer['type'] = '无';
            }

            $buffer['link'] = base64_encode($link[1][$key]);

            $search[$key] = $buffer;
        }

        return $search;
    }

    public static function recordSearch($hotWord, $list)
    {
        $jsonHotSearch = self::saveInfo('searchHistory');

        if (!empty($jsonHotSearch)) {
            $arrHotSearch = json_decode($jsonHotSearch, true);  //解析为数组格式
            if (array_key_exists($hotWord, $arrHotSearch)) { //有记录则加一
                $arrHotSearch[$hotWord] += 1;

                if ($arrHotSearch[$hotWord] == max($arrHotSearch)) {    //搜索最多的作为默认列表
                    self::saveInfo('defaultSearch', $list);
                }
            } else {  //无记录则在数组中创建
                $arrHotSearch[$hotWord] = 1;
            }

            $jsonHotSearch = json_encode($arrHotSearch);
        } else {  //文件为空
            $arrHotSearch = [$hotWord => 1];
            $jsonHotSearch = json_encode($arrHotSearch);
            self::saveInfo('defaultSearch', $list);
        }

        self::saveInfo('searchHistory', $jsonHotSearch);
    }

    public static function saveInfo($dir, $new = '')
    {
        $filePath = 'Cinema/' . $dir . '.txt';

        if (file_exists($filePath)) {
            if (empty($new)) {  //$new为空时是读取状态，不为空时为写入状态
                //文件不为空时返回文件内容，为空时返回json格式的空
                if (filesize($filePath)) {
                    $fp = fopen($filePath, "r");
                    $fJson = fread($fp, filesize($filePath));     //指定读取大小，这里把整个文件内容读取出来
                    fclose($fp);
                } else {
                    $fJson = '{}';
                }

                return $fJson;
            } else {
                $fp = fopen($filePath, "w");
                flock($fp, LOCK_EX);
                fwrite($fp, $new);
                flock($fp, LOCK_UN);
                fclose($fp);

                return true;
            }
        }

        return false;
    }

    public static function getHistory($max = 10, $dir = 'searchHistory')
    {
        $jsonHotSearch = self::saveInfo($dir);

        if (!empty($jsonHotSearch)) {
            $arrHotSearch = json_decode($jsonHotSearch, true);  //解析为数组格式

            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                arsort($arrHotSearch);  //按从多到少排序
                $arrHotSearch = array_keys($arrHotSearch);  //将关键词（键）保存为新数组
            } else {
                @arsort($arrHotSearch);  //按从多到少排序
                $arrHotSearch = @array_keys($arrHotSearch);  //将关键词（键）保存为新数组
            }

            $hotWordNum = count($arrHotSearch);

            if ($hotWordNum > 0 && $max == 1) {
                return $arrHotSearch[0];
            } else {
                $res = '';
                for ($i = 0; $i < ($hotWordNum > $max ? $max : $hotWordNum); $i++) {    //最多显示$max个热搜词
                    $res .= "<li><a href='search.php?kw={$arrHotSearch[$i]}'>{$arrHotSearch[$i]}</a></li>";
                }
                return $res;
            }
        } else {  //文件为空
            return "<li><a>数据为空</a></li>";
        }
    }

    public static function clickRec($dir, $name)
    {
        //确保保存的记录文件名不为空
        if (!empty($name)) {
            $jsonRes = self::saveInfo($dir);

            if (!empty($jsonRes)) {
                $arrRes = json_decode($jsonRes, true);  //解析为数组格式
                if (array_key_exists($name, $arrRes)) { //有记录则加一
                    $arrRes[$name] += 1;
                } else {  //无记录则在数组中创建
                    $arrRes[$name] = 1;
                }

                $jsonRes = json_encode($arrRes);
            } else {  //文件为空
                $arrRes = [$name => 1];
                $jsonRes = json_encode($arrRes);
            }

            self::saveInfo($dir, $jsonRes);
        }
    }

    public static $parser = "<div id=\"parsers\">
                <button onclick=\"vParser('http://api.wlzhan.com/sudu/?url=')\">解析器一</button>
                <button onclick=\"vParser('https://api.47ks.com/webcloud/?v=')\">解析器二</button>
                <button onclick=\"vParser('https://api.flvsp.com/?url=')\">解析器三</button>
                <button onclick=\"vParser('http://api.xfsub.com/index.php?url=')\">解析器四</button>
                <button onclick=\"vParser('http://aikan-tv.com/?url=')\">解析器五</button>
                <button onclick=\"vParser('http://j.zz22x.com/jx/?url=')\">解析器六</button>
                <button onclick=\"vParser('http://jiexi.071811.cc/jx2.php?url=')\">解析器七</button>
            </div>";
}