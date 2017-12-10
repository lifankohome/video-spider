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

    public static function getMovies($cat = 'all', $page = 1)
    {
        $dom = file_get_contents('http://www.360kan.com/dianying/list.php?year=all&area=all&act=all&cat=' . $cat . '&pageno=' . $page);

        $movieNameDom = '#<span class="s1">(.*?)</span>#';
        $movieScoreDom = '#<span class="s2">(.*?)</span>#';
        $movieYearDom = '#<span class="hint">(.*?)</span>#';
        $movieLinkDom = '#<a class="js-tongjic" href="(.*?)">#';
        $movieActorDom = '# <p class="star">(.*?)</p>#';
        $movieImgDom = '#<div class="cover g-playicon">
                                <img src="(.*?)">#';
        $movieCatDom = '#<a class="js-tongjip" href="http://www.360kan.com/dianying/list.php\?year=all\&area=all\&act=all\&cat=(.*?)" target="_self">(.*?)</a>#';

        preg_match_all($movieNameDom, $dom, $movieName);
        preg_match_all($movieScoreDom, $dom, $movieScore);
        preg_match_all($movieYearDom, $dom, $movieYear);
        preg_match_all($movieLinkDom, $dom, $movieLink);
        preg_match_all($movieActorDom, $dom, $movieActor);
        preg_match_all($movieImgDom, $dom, $movieImg);
        preg_match_all($movieCatDom, $dom, $movieCat);

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
        foreach ($movieCat[2] as $key => $val) {
            $movieCatArr[$movieCat[1][$key]] = $val;
        }

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

    public static function getTvs($cat = 'all', $page = 1)
    {
        $dom = file_get_contents('http://www.360kan.com/dianshi/list.php?year=all&area=all&act=all&cat=' . $cat . '&pageno=' . $page);

        $tvNameDom = '#<span class="s1">(.*?)</span>#';
        $tvLinkDom = '#<a class="js-tongjic" href="(.*?)">#';
        $tvActorDom = '# <p class="star">(.*?)</p>#';
        $tvImgDom = '#<div class="cover g-playicon">
                                <img src="(.*?)">#';
        $tvUpdateDom = '#<span class="hint">(.*?)</span> #';
        $tvCatDom = '#<a class="js-tongjip" href="http://www.360kan.com/dianshi/list.php\?year=all\&area\=all\&act\=all\&cat\=(.*?)" target="_self">(.*?)</a>#';

        preg_match_all($tvLinkDom, $dom, $tvLink);
        preg_match_all($tvNameDom, $dom, $tvName);
        preg_match_all($tvImgDom, $dom, $tvImg);
        preg_match_all($tvActorDom, $dom, $tvActor);
        preg_match_all($tvUpdateDom, $dom, $tvUpdate);
        preg_match_all($tvCatDom, $dom, $tvCat);

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
        foreach ($tvCat[2] as $key => $val) {
            $tvCatArr[$tvCat[1][$key]] = $val;
        }

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

    public static function getVarieties($cat = 'all', $page = 1)
    {
        $dom = file_get_contents('http://www.360kan.com/zongyi/list?act=all&area=all&cat='.$cat.'&pageno=' . $page);

        $varietyNameDom = '#<span class="s1">(.*?)</span>#';
        $varietyLinkDom = '#<a class="js-tongjic" href="(.*?)">#';
        $varietyActorDom = '# <p class="star">(.*?)</p>#';
        $varietyImgDom = '#<div class="cover g-playicon">
                                <img src="(.*?)">#';
        $varietyUpdateDom = '#<span class="hint">(.*?)</span> #';
        $varietyCatDom = '#<a class="js-tongjip" href="http://www.360kan.com/zongyi/list\?act\=all\&area\=all\&cat\=(.*?)" target="_self">(.*?)</a>#';

        preg_match_all($varietyLinkDom, $dom, $varietyLink);
        preg_match_all($varietyNameDom, $dom, $varietyName);
        preg_match_all($varietyImgDom, $dom, $varietyImg);
        preg_match_all($varietyActorDom, $dom, $varietyActor);
        preg_match_all($varietyUpdateDom, $dom, $varietyUpdate);
        preg_match_all($varietyCatDom, $dom, $varietyCat);

        $teleplays = array();
        foreach ($varietyName[1] as $key => $value) {
            $buffer['link'] = base64_encode('https://www.360kan.com' . $varietyLink[1][$key]);
            $buffer['name'] = $varietyName[1][$key];
            $buffer['img'] = $varietyImg[1][$key];
            $buffer['actor'] = $varietyActor[1][$key];
            $buffer['update'] = $varietyUpdate[1][$key];

            $teleplays[$key] = $buffer;
        }

        array_pop($varietyCat[2]);  //最后一个元素是【真人秀】，只有这一个分类是三个字，影响排版，所以去掉不要

        $varietyCatArr = array();
        foreach ($varietyCat[2] as $key => $val) {
            $varietyCatArr[$varietyCat[1][$key]] = $val;
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

    public static $parser = "
    <div id=\"parsers\">
                <button onclick=\"vParser('http://api.wlzhan.com/sudu/?url=')\">解析器一</button>
                <button onclick=\"vParser('https://api.47ks.com/webcloud/?v=')\">解析器二</button>
                <button onclick=\"vParser('http://www.efunfilm.com/yunparse/index.php?url=')\">解析器三</button>
                <button onclick=\"vParser('http://api.nepian.com/ckparse/?url=')\">解析器四</button>
                <button onclick=\"vParser('http://aikan-tv.com/?url=')\">解析器五</button>
                <button onclick=\"vParser('http://j.zz22x.com/jx/?url=')\">解析器六</button>
                <button onclick=\"vParser('http://jiexi.071811.cc/jx2.php?url=')\">解析器七</button>
                <button onclick=\"vParser('http://api.wlzhan.com/sudu/?url=')\">解析器八</button>
                <button onclick=\"vParser('http://api.xfsub.com/index.php?url=')\">解析器九</button>
                <button onclick=\"vParser('https://api.flvsp.com/?url=')\">解析器十</button>
            </div>";
}