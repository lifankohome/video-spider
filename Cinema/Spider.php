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
        $presentCat = ['all' => '热门推荐', '103' => '喜剧', '100' => '爱情', '106' => '动作', '102' => '恐怖', '104' => '科幻', '112' => '剧情', '105' => '犯罪', '113' => '奇幻', '108' => '战争', '115' => '悬疑', '107' => '动画', '117' => '文艺', '101' => '伦理', '118' => '纪录', '119' => '传记', '120' => '歌舞', '121' => '古装', '122' => '历史', '123' => '惊悚', 'other' => '其他'];

        $dom = file_get_contents('https://www.360kan.com/dianying/list');

        $movieCatDom = '#<a class="js-tongjip js-chose-item" data-type="cat" data-item="(.*?)" href="javascript:;" target="_self">(.*?)\s#';

        preg_match_all($movieCatDom, $dom, $movieCat);

        $movieCatArr = array();
        foreach ($movieCat[1] as $key => $val) {
            $movieCatArr[$val] = $movieCat[2][$key];
        }

        self::setMoviesCat($movieCatArr);
        self::setPresentCat($presentCat[$cat]);

        $dom = file_get_contents('https://www.360kan.com/dianying/listajax?rank=rankhot&cat=' . $cat . '&year=all&area=all&pageno=' . $page);

        return json_decode($dom, true)['data']['list'];
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
        $presentCat = ['all' => '热门推荐', '101' => '言情', '105' => '伦理', '109' => '喜剧', '108' => '悬疑', '111' => '都市', '100' => '偶像', '104' => '古装', '107' => '军事', '103' => '警匪', '112' => '历史', '102' => '宫廷', '116' => '励志', '117' => '神话', '118' => '谍战', '119' => '青春', '120' => '家庭', '115' => '动作', '114' => '情景', '106' => '武侠', '113' => '科幻', 'other' => '其他'];

        $dom = file_get_contents('https://www.360kan.com/dianshi/list');

        $tvCatDom = '#<a class="js-tongjip js-chose-item" data-type="cat" data-item="(.*?)" href="javascript:;" target="_self">(.*?)\s#';

        preg_match_all($tvCatDom, $dom, $tvCat);

        $tvCatArr = array();
        foreach ($tvCat[1] as $key => $val) {
            $tvCatArr[$val] = $tvCat[2][$key];
        }

        //为了页面美观，仅保留显示前20个分类
        $tvCatArr = array_slice($tvCatArr, 0, 20, true);

        self::setTvCat($tvCatArr);
        self::setPresentCat($presentCat[$cat]);

        $dom = file_get_contents('https://www.360kan.com/dianshi/listajax?rank=rankhot&cat=' . $cat . '&year=all&area=all&pageno=' . $page);

        return json_decode($dom, true)['data']['list'];
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
        $presentCat = ['all' => '热门推荐', '101' => '选秀', '102' => '八卦', '103' => '访谈', '104' => '情感', '105' => '生活', '106' => '晚会', '107' => '搞笑', '108' => '音乐', '109' => '时尚', '110' => '游戏', '111' => '少儿', '112' => '体育', '113' => '纪实', '114' => '科教', '115' => '曲艺', '116' => '歌舞', '117' => '财经', '118' => '汽车', '119' => '播报', 'other' => ''];

        $dom = file_get_contents('https://www.360kan.com/zongyi/list');

        $varietyCatDom = '#<a class="js-tongjip js-chose-item" data-type="cat" data-item="(.*?)" href="javascript:;" target="_self">(.*?)\s#';

        preg_match_all($varietyCatDom, $dom, $varietyCat);

        $varietyCatArr = array();
        foreach ($varietyCat[1] as $key => $val) {
            //为了页面美观，仅保留分类长度为2个字的综艺
            if (mb_strlen($varietyCat[2][$key]) <= 2) {
                $varietyCatArr[$val] = $varietyCat[2][$key];
            }
        }

        self::setVarietyCat($varietyCatArr);
        self::setPresentCat($presentCat[$cat]);

        $dom = file_get_contents('https://www.360kan.com/zongyi/listajax?rank=rankhot&cat=' . $cat . '&year=all&area=all&pageno=' . $page);

        return json_decode($dom, true)['data']['list'];
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