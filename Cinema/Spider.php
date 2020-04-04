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
    private static $varietyCat = array();
    private static $teleplayCat = array();
    private static $animeCat = array();
    private static $presentCat = '';

    private static $slider = null;
    private static $rank = null;

    public static function getPresentCat()
    {
        return self::$presentCat;
    }

    private static function setPresentCat($presentCat)
    {
        self::$presentCat = $presentCat;
    }

    public static function curl_get_contents($url)
    {
        $user_agent = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.81 Safari/537.36';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取数据返回
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 若发生了跳转则获取跳转后的内容
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    /**
     * @param string $cat
     * @param int $page
     * @return array
     */
    public static function getMovies($cat = 'all', $page = 1)
    {
        $presentCat = ['all' => '热门推荐', '103' => '喜剧', '100' => '爱情', '106' => '动作', '102' => '恐怖', '104' => '科幻', '112' => '剧情', '105' => '犯罪', '113' => '奇幻', '108' => '战争', '115' => '悬疑', '107' => '动画', '117' => '文艺', '101' => '伦理', '118' => '纪录', '119' => '传记', '120' => '歌舞', '121' => '古装', '122' => '历史', '123' => '惊悚', 'other' => '其他'];

        $dom = self::curl_get_contents('https://www.360kan.com/dianying/list?year=all&area=all&act=all&cat=' . $cat);

        $movieCatDom = '/<a class="js-tongjip" href=".+year=all&area=all&act=all&cat=(.*?)" target="_self">(.*?)\s/';

        preg_match_all($movieCatDom, $dom, $movieCat);

        $movieCatArr = array();
        foreach ($movieCat[1] as $key => $val) {
            $movieCatArr[$val] = $movieCat[2][$key];

            if (count($movieCatArr) == 20) {
                break;
            }
        }

        self::setMoviesCat($movieCatArr);
        self::setPresentCat($presentCat[$cat]);

        $movieNameDom = '/<span class="s1">(.*?)<\/span>/';
        $movieScoreDom = '/<span class="hint">[\w]+<\/span>[\s]+(.*?)<\/div>/';
        $movieYearDom = '/<span class="hint">(.*?)<\/span>/';
        $movieLinkDom = '/<a class="js-tongjic" href="(.*?)">/';
        $movieActorDom = '/<p class="star">(.*?)<\/p>/';
        $movieImgDom = '/<div class="cover g-playicon">\s+<img src="(.*?)">/';

        preg_match_all($movieNameDom, $dom, $movieName);
        preg_match_all($movieScoreDom, $dom, $movieScore);
        preg_match_all($movieYearDom, $dom, $movieYear);
        preg_match_all($movieLinkDom, $dom, $movieLink);
        preg_match_all($movieActorDom, $dom, $movieActor);
        preg_match_all($movieImgDom, $dom, $movieImg);

        $movies = array();
        foreach ($movieName[1] as $key => $value) {
            $buffer['title'] = $movieName[1][$key];
            $buffer['point'] = empty($movieScore[1][$key]) ? '无' : $movieScore[1][$key];
            $buffer['tag'] = $movieYear[1][$key];
            $buffer['coverpage'] = $movieLink[1][$key];
            $buffer['desc'] = mb_substr($movieActor[1][$key], 3);
            $buffer['cover'] = $movieImg[1][$key];

            $movies[$key] = $buffer;
        }

        return $movies;
    }

    public static function getSlider($type = 'dianying')
    {
        $dom = self::curl_get_contents('https://www.360kan.com/' . $type . '/index.html');

        $offset = 10000;

        if ($type == 'dongman') {
            for ($k = 0; $k < 5; $k++) {
                $slider_start = mb_strpos($dom, '<ul class="b-topslider-list js-slide-list">', $offset);
                if ($slider_start === false) {
                    $offset -= 1000;
                } else {
                    break;
                }
            }
            $slider_end = mb_strpos($dom, '<ul class="b-topslider-btns js-slide-btns">', $slider_start);
            $slider = mb_substr($dom, $slider_start, $slider_end - $slider_start);
        } else {
            for ($k = 0; $k < 5; $k++) {
                $slider_start = mb_strpos($dom, '<ul class="b-topslidernew-list js-slide-list">', $offset);
                if ($slider_start === false) {
                    $offset -= 1000;
                } else {
                    break;
                }
            }
            $slider_end = mb_strpos($dom, '<ul class="b-topslidernew-btns js-slide-btns">', $slider_start);
            $slider = mb_substr($dom, $slider_start, $slider_end - $slider_start);
        }
        $slider = str_replace(' href="', ' target="_blank" href="', $slider);
        self::$slider = '<div class="slider"><ul id="nav"></ul>' . str_replace('https://www.360kan.com', 'play.php?play=', $slider) . '</div>';

        return self::$slider;
    }

    public static function getRank()
    {
        $dom = self::curl_get_contents('https://www.360kan.com/rank/index');

        $rank_start = mb_strpos($dom, '<ul class="p-cat-videolist">', 15000);
        $rank_end = mb_strpos($dom, '</ul>', $rank_start);

        $rank = mb_substr($dom, $rank_start, $rank_end - $rank_start) . '</ul>';

        $rank = str_replace(' href="', ' target="_blank" href="', $rank);
        self::$rank = '<div class="rank"><ul class="p-cat-videolist" style="font-weight: 600">
        <li class="p-cat-video" style="border-bottom: 1px solid rgba(240, 240, 240, 0.5);">
            <a><span class="p-cat-rank p-cat-topthree" style="margin-left: 5px"></span><span class="p-cat-videoname">播放量排行榜</span>
                <span class="p-cat-playcount" style="font-size: 14px">播放量</span></a></li></ul>' .
            str_replace('http://www.360kan.com', 'play.php?play=', $rank) . '</div>';

        return self::$rank;
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
    public static function getTeleplays($cat = 'all', $page = 1)
    {
        $presentCat = ['all' => '热门推荐', '101' => '言情', '105' => '伦理', '109' => '喜剧', '108' => '悬疑', '111' => '都市', '100' => '偶像', '104' => '古装', '107' => '军事', '103' => '警匪', '112' => '历史', '102' => '宫廷', '116' => '励志', '117' => '神话', '118' => '谍战', '119' => '青春', '120' => '家庭', '115' => '动作', '114' => '情景', '106' => '武侠', '113' => '科幻', 'other' => '其他'];

        $dom = self::curl_get_contents('https://www.360kan.com/dianshi/list.php?year=all&area=all&act=all&cat=' . $cat);

        $teleplayCatDom = '/<a class="js-tongjip" href=".+year=all&area=all&act=all&cat=(.*?)" target="_self">(.*?)\s/';

        preg_match_all($teleplayCatDom, $dom, $teleplayCat);

        $teleplayCatArr = array();
        foreach ($teleplayCat[1] as $key => $val) {
            $teleplayCatArr[$val] = $teleplayCat[2][$key];
            if (count($teleplayCatArr) == 20) {
                break;
            }
        }

        //为了页面美观，仅保留显示前20个分类
        $teleplayCatArr = array_slice($teleplayCatArr, 0, 20, true);

        self::setTeleplayCat($teleplayCatArr);
        self::setPresentCat($presentCat[$cat]);

        $tvNameDom = '/<span class="s1">(.*?)<\/span>/';
        $tvUpdateDom = '/<span class="hint">(.*?)<\/span>/';
        $tvLinkDom = '/<a class="js-tongjic" href="(.*?)">/';
        $tvActorDom = '/<p class="star">(.*?)<\/p>/';
        $tvImgDom = '/<div class="cover g-playicon">\s+<img src="(.*?)">/';

        preg_match_all($tvNameDom, $dom, $tvName);
        preg_match_all($tvUpdateDom, $dom, $tvUpdate);
        preg_match_all($tvLinkDom, $dom, $tvLink);
        preg_match_all($tvActorDom, $dom, $tvActor);
        preg_match_all($tvImgDom, $dom, $tvImg);

        $teleplays = array();
        foreach ($tvName[1] as $key => $value) {
            $buffer['title'] = $tvName[1][$key];
            $buffer['tag'] = $tvUpdate[1][$key];
            $buffer['coverpage'] = $tvLink[1][$key];
            $buffer['desc'] = $tvActor[1][$key];
            $buffer['cover'] = $tvImg[1][$key];

            $teleplays[$key] = $buffer;
        }

        return $teleplays;
    }

    public static function getTeleplayCat()
    {
        return self::$teleplayCat;
    }

    private static function setTeleplayCat($teleplayCat)
    {
        self::$teleplayCat = $teleplayCat;
    }

    /**
     * @param string $cat
     * @param int $page
     * @return array
     */
    public static function getVarieties($cat = 'all', $page = 1)
    {
        $presentCat = ['all' => '热门推荐', '101' => '选秀', '102' => '八卦', '103' => '访谈', '104' => '情感', '105' => '生活', '106' => '晚会', '107' => '搞笑', '108' => '音乐', '109' => '时尚', '110' => '游戏', '111' => '少儿', '112' => '体育', '113' => '纪实', '114' => '科教', '115' => '曲艺', '116' => '歌舞', '117' => '财经', '118' => '汽车', '119' => '播报', 'other' => ''];

        $dom = self::curl_get_contents('https://www.360kan.com/zongyi/list?act=all&area=all&cat=' . $cat);

        $varietyCatDom = '/<a class="js-tongjip" href=".+act=all&area=all&cat=(.*?)" target="_self">(.*?)\s/';

        preg_match_all($varietyCatDom, $dom, $varietyCat);

        $varietyCatArr = array();
        foreach ($varietyCat[1] as $key => $val) {
            //为了页面美观，仅保留分类长度为2个字的综艺
            if (mb_strlen($varietyCat[2][$key]) <= 2) {
                $varietyCatArr[$val] = $varietyCat[2][$key];
            }
            if (count($varietyCatArr) == 20) {
                break;
            }
        }

        self::setVarietyCat($varietyCatArr);
        self::setPresentCat($presentCat[$cat]);

        $varietyNameDom = '/<span class="s1">(.*?)<\/span>/';
        $varietyUpdateDom = '/<span class="hint">(.*?)<\/span>/';
        $varietyLinkDom = '/<a class="js-tongjic" href="(.*?)">/';
        $varietyActorDom = '/<p class="star">(.*?)<\/p>/';
        $varietyImgDom = '/<div class="cover g-playicon">\s+<img src="(.*?)">/';

        preg_match_all($varietyNameDom, $dom, $varietyName);
        preg_match_all($varietyUpdateDom, $dom, $varietyUpdate);
        preg_match_all($varietyLinkDom, $dom, $varietyLink);
        preg_match_all($varietyActorDom, $dom, $varietyActor);
        preg_match_all($varietyImgDom, $dom, $varietyImg);

        $teleplays = array();
        foreach ($varietyName[1] as $key => $value) {
            $buffer['title'] = $varietyName[1][$key];
            $buffer['tag'] = $varietyUpdate[1][$key];
            $buffer['coverpage'] = $varietyLink[1][$key];
            $buffer['desc'] = $varietyActor[1][$key];
            $buffer['cover'] = $varietyImg[1][$key];

            $teleplays[$key] = $buffer;
        }

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
     * @param string $cat
     * @param int $page
     * @return array
     */
    public static function getAnimes($cat = 'all', $page = 1)
    {
        $presentCat = ['all' => '最近热播', '100' => '热血', '134' => '科幻', '109' => '魔幻', '135' => '经典', '136' => '励志', '111' => '少儿', '107' => '冒险', '105' => '搞笑', '137' => '推理', '101' => '恋爱', '138' => '治愈', '106' => '幻想', '104' => '校园', '110' => '动物', '112' => '机战', '131' => '亲子', '139' => '儿歌', '103' => '运动', '108' => '悬疑', '113' => '怪物'];

        $dom = self::curl_get_contents('https://www.360kan.com/dongman/list?year=all&area=all&cat=' . $cat);
        $dom .= self::curl_get_contents('https://www.360kan.com/dongman/list?year=all&area=all&cat=' . $cat . '&pageno=2');

        $animeCatDom = '/<a class="js-tongjip" href=".+year=all&area=all&cat=(.*?)" target="_self">(.*?)\s/';

        preg_match_all($animeCatDom, $dom, $animeCat);

        $animeCatArr = array();
        foreach ($animeCat[1] as $key => $val) {
            //为了页面美观，仅保留分类长度为2个字的动漫
            if (mb_strlen($animeCat[2][$key]) <= 2) {
                $animeCatArr[$val] = $animeCat[2][$key];
            }
            // 仅显示20个分类
            if (count($animeCatArr) == 20) {
                break;
            }
        }

        self::setanimeCat($animeCatArr);
        self::setPresentCat($presentCat[$cat]);

        $animeNameDom = '/<span class="s1">(.*?)<\/span>/';
        $animeUpdateDom = '/<span class="hint">(.*?)<\/span>/';
        $animeLinkDom = '/<a class="js-tongjic" href="(.*?)">/';
        $animeImgDom = '/<div class="cover g-playicon">\s+<img src="(.*?)">/';

        preg_match_all($animeNameDom, $dom, $animeName);
        preg_match_all($animeUpdateDom, $dom, $animeUpdate);
        preg_match_all($animeLinkDom, $dom, $animeLink);
        preg_match_all($animeImgDom, $dom, $animeImg);

        $animes = array();
        foreach ($animeName[1] as $key => $value) {
            $buffer['title'] = $animeName[1][$key];
            $buffer['tag'] = $animeUpdate[1][$key];
            $buffer['coverpage'] = $animeLink[1][$key];
            $buffer['cover'] = $animeImg[1][$key];

            $animes[$key] = $buffer;
        }
        array_pop($animes);
        array_pop($animes);

        return $animes;
    }

    public static function getAnimeCat()
    {
        return self::$animeCat;
    }

    private static function setAnimeCat($animeCat)
    {
        self::$animeCat = $animeCat;
    }

    /**
     * @param $kw
     * @return array
     */
    public static function search($kw)
    {
        if (empty($kw)) {
            $dom = self::curl_get_contents('https://www.360kan.com/dianying/list?year=all&area=all&act=all&cat=all');
        } else {
            if (substr($kw, 0, 4) == 'http' || strpos($kw, ".com")) {
                header("location:parse.php?url=$kw");
            }
            $dom = self::curl_get_contents('https://so.360kan.com/index.php?kw=' . $kw);
        }

        $nameDom = '/js-playicon" title="(.*?)"\s*data/';
        $linkDom = '/a href="(.*?)" class="g-playicon js-playicon"/';
        $imgDom = '/js-playicon" title="(.*?)\s{0,}<img src="(.*?)" alt="(.*?)" \/>/';
        $scoreDom = '/<div class="m-score">(.*?)<\/div>/';
        $descDom = '/<p>(<i>(.*?)<\/i>){0,}(.*?)<\/p>/';

        preg_match_all($nameDom, $dom, $name);
        preg_match_all($linkDom, $dom, $link);
        preg_match_all($imgDom, $dom, $img);
        preg_match_all($scoreDom, $dom, $score);
        preg_match_all($descDom, $dom, $desc);

        $search = array();
        foreach ($name[1] as $key => $value) {
            $buffer['name'] = $name[1][$key];

            if (isset($img[2][$key])) {
                $buffer['img'] = $img[2][$key];
            } else {
                $buffer['img'] = 'img/noCover.jpg';
            }

            if (!empty($score[1][$key])) {
                $buffer['score'] = $score[1][$key];
            } else {
                $buffer['score'] = '无';
            }

            if (isset($desc[3][$key])) {
                $buffer['desc'] = $desc[3][$key];
            } else {
                $buffer['desc'] = '无';
            }

            $buffer['link'] = substr($link[1][$key], 21);

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
}
