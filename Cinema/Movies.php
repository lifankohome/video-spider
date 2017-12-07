<?php
/**
 * Created by PhpStorm.
 * User: lifanko  lee
 * Date: 2017/12/6
 * Time: 13:25
 */

namespace Cinema;

class Movies
{
    public static function getMovies($page = 1)
    {
        $dom = file_get_contents('http://www.360kan.com/dianying/list.php?rank=rankhot&cat=all&area=all&act=all&year=all&pageno=' . $page);

        $movieNameDom = '#<span class="s1">(.*?)</span>#';
        $movieScoreDom = '#<span class="s2">(.*?)</span>#';
        $movieYearDom = '#<span class="hint">(.*?)</span>#';
        $movieLinkDom = '#<a class="js-tongjic" href="(.*?)">#';
        $movieActorDom = '# <p class="star">(.*?)</p>#';
        $movieImgDom = '#<div class="cover g-playicon">
                                <img src="(.*?)">#';
        preg_match_all($movieNameDom, $dom, $movieName);
        preg_match_all($movieScoreDom, $dom, $movieScore);
        preg_match_all($movieYearDom, $dom, $movieYear);
        preg_match_all($movieLinkDom, $dom, $movieLink);
        preg_match_all($movieActorDom, $dom, $movieActor);
        preg_match_all($movieImgDom, $dom, $movieImg);

        $movie = array();
        foreach ($movieName[1] as $key => $value) {
            $buffer['link'] = base64_encode('http://www.360kan.com' . $movieLink[1][$key]);
            $buffer['name'] = $movieName[1][$key];
            $buffer['score'] = $movieScore[1][$key];
            $buffer['img'] = $movieImg[1][$key];
            $buffer['year'] = $movieYear[1][$key];
            $buffer['actor'] = $movieActor[1][$key];

            $movie[$key] = $buffer;
        }

        return $movie;
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