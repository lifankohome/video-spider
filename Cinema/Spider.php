<?php
/**
 * Created by PhpStorm.
 * User: lifanko  lee
 * Date: 2017/12/6
 * Time: 13:25
 */

namespace Cinema;

require_once 'Temp.php';

class Spider
{
    const host = 'https://www.360kan.com/';
    const callback = 'video_spider';

    private static $slider = null;
    private static $rank = null;
    public static $filter = null;

    /**
     * 获取轮播图
     * @param string $type
     * @return array
     */
    public static function getSlider($type = 'dianying')
    {
        $temp = new Temp('Slider_' . $type);
        $res = $temp->get();

        if ($res) {
            self::$slider = $res;
        } else {
            $dom = self::curl_get_contents('https://api.web.360kan.com/v1/block?blockid=99&callback=video_spider&extra=' . $type);

            $callback_len = strlen(self::callback);
            if (substr($dom, 0, $callback_len) == self::callback) {
                $dom = substr($dom, $callback_len + 1, -2);
            }

            $res = json_decode($dom);
            if ($res->errno != 0) {
                return [0, $res->msg];
            }

            return false;

            $dom = self::curl_get_contents(self::host . $type . '/index.html');

            $offset = 10000;

            if ($type == 'dongman') {
                for ($k = 0; $k < 5; $k++) {
                    $slider_start = mb_strpos($dom, '<ul class="b-topslidernew-list js-slide-list">', $offset);
                    if ($slider_start === false) {
                        $offset -= 1000;
                    } else {
                        break;
                    }
                }
                $slider_end = mb_strpos($dom, '<ul class="b-topslidernew-btns b-topslidernew-first-on js-slide-btns">', $slider_start);
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
                $slider_end = mb_strpos($dom, '<ul class="b-topslidernew-btns b-topslidernew-first-on js-slide-btns">', $slider_start);
                $slider = mb_substr($dom, $slider_start, $slider_end - $slider_start);
            }

            // Remove ad list
            $remove = ['www.360kan.com/special/', '7477.com', 'c.ssp.360.cn'];

            foreach ($remove as $item) {
                while ($special_pos = strpos($slider, $item)) {
                    $special_pos_start = strrpos(substr($slider, 0, $special_pos), '<li');
                    $special_pos_end = strpos($slider, '</li>', $special_pos_start) + 5;
                    $slider = substr($slider, 0, $special_pos_start) . substr($slider, $special_pos_end);
                }
            }

            $slider = str_replace(' href="', ' target="_blank" href="', $slider);
            $slider = str_replace(self::host . 'vp/', self::host, $slider);
            self::$slider = '<div class="slider"><ul id="nav"></ul>' . str_replace(self::host, 'play.php?play=/', $slider) . '</div>';

            $temp->save(self::$slider);
        }

        return self::$slider;
    }

    /**
     * 获取排行榜
     * @return string|null
     */
    public static function getRank()
    {
        $temp = new Temp('Rank');
        $res = $temp->get();

        if ($res) {
            self::$rank = $res;
        } else {
            $dom = self::curl_get_contents(self::host . 'rank/index');

            $rank_start = mb_strpos($dom, '<ul class="p-cat-videolist">', 15000);
            $rank_end = mb_strpos($dom, '</ul>', $rank_start);

            $rank = mb_substr($dom, $rank_start, $rank_end - $rank_start) . '</ul>';

            $rank = str_replace(' href="', ' target="_blank" href="', $rank);
            self::$rank = '<div class="rank"><ul class="p-cat-videolist" style="font-weight: 600">
            <li class="p-cat-video" style="border-bottom: 1px solid rgba(240, 240, 240, 0.5);background-color: rgba(0, 0, 0, 0);">
                <a><span class="p-cat-rank p-cat-topthree" style="margin-left: 5px"></span><span class="p-cat-videoname" style="font-size: 16px">播放量排行榜</span>
                    <span class="p-cat-playcount" style="font-size: 16px">点击量</span></a></li></ul>' .
                str_replace(self::host, 'play.php?play=/', $rank) . '</div>';

            $temp->save(self::$rank);
        }

        return self::$rank;
    }

    /**
     * 获取电影列表
     * @param $opt
     * @return array
     */
    public static function getMovies($opt)
    {
        $temp = new Temp('Movies_' . $opt);
        $res = $temp->get();

        if ($res) {
            self::$filter = $res['filter'];

            return $res['list'];
        } else {
            $dom = self::curl_get_contents('https://api.web.360kan.com/v1/filter/list?callback=video_spider&catid=1&extra' . $opt);

            $callback_len = strlen(self::callback);
            if (substr($dom, 0, $callback_len) == self::callback) {
                $dom = substr($dom, $callback_len + 1, -2);
            }

            $res = json_decode($dom);
            if ($res->errno != 0) {
                return [0, $res->msg];
            }

            $data = $res->data;
            $movies = array();
            foreach ($data->movies as $value) {
                $buffer = [
                    'title' => $value->title,
                    'point' => $value->comment,
                    'tag' => $value->pubdate,
                    'link' => $value->id,
                    'desc' => mb_substr(implode(', ', $value->actor), 0, 29),
                    'cover' => $value->cdncover,
                ];
                array_push($movies, $buffer);
            }

            return $movies;

            $dom = self::curl_get_contents(self::host . 'dianying/list.php?' . $opt);

            $filter_start = '<div class="s-filter">';
            $filter_end = '<div class="js-tab-container"';
            $filter_start = strpos($dom, $filter_start);
            $filter_end = strpos($dom, $filter_end, $filter_start) - 18;

            $filter_buffer = substr($dom, $filter_start, $filter_end - $filter_start);
            self::$filter = str_replace(self::host . 'dianying/list.php', 'index.php', $filter_buffer);

            $dom .= self::curl_get_contents(self::host . 'dianying/list.php?' . $opt . '&pageno=2');
            $dom .= self::curl_get_contents(self::host . 'dianying/list.php?' . $opt . '&pageno=3');

            $movieNameDom = '/<span class="s1">[^{](.*?)<\/span>/';
            $movieScoreDom = '/<span class="hint">[\w]+<\/span>[\s]+(.*?)\s*<\/div>/';
            $moviePayDom = '/<div class="cover g-playicon">\s+<img.*\s+(.*?)\s+<div/';
            $movieYearDom = '/<span class="hint">(.*?)<\/span>/';
            $movieLinkDom = '/<a class="js-tongjic" href="(.*?)"/';
            $movieActorDom = '/<p class="star">主演：\s?(.*?)<\/p>/';
            $movieImgDom = '/<div class="cover g-playicon">\s+<img src="(.*?)">/';

            preg_match_all($movieNameDom, $dom, $movieName);
            preg_match_all($movieScoreDom, $dom, $movieScore);
            preg_match_all($moviePayDom, $dom, $moviePay);
            preg_match_all($movieYearDom, $dom, $movieYear);
            preg_match_all($movieLinkDom, $dom, $movieLink);
            preg_match_all($movieActorDom, $dom, $movieActor);
            preg_match_all($movieImgDom, $dom, $movieImg);

            $movies = array();
            foreach ($movieName[1] as $key => $value) {
                $buffer['title'] = substr($movieName[0][$key], 17, -7);
                $buffer['point'] = (empty($movieScore[1][$key]) ? '暂无评分' : $movieScore[1][$key]) . (empty($moviePay[1][$key]) ? '' : '<span class="pay">付费</span>');
                $buffer['tag'] = empty($movieYear[1][$key]) ? '无' : $movieYear[1][$key];
                $buffer['coverpage'] = $movieLink[1][$key];
                $buffer['desc'] = $movieActor[1][$key];
                $buffer['cover'] = $movieImg[1][$key];

                $movies[$key] = $buffer;
            }

            $temp->save(['filter' => self::$filter, 'list' => $movies]);

            return $movies;
        }
    }

    /**
     * 获取综艺列表
     * @param $opt
     * @return array
     */
    public static function getVarieties($opt)
    {
        $temp = new Temp('Varieties_' . $opt);
        $res = $temp->get();

        if ($res) {
            self::$filter = $res['filter'];

            return $res['list'];
        } else {
            $dom = self::curl_get_contents(self::host . 'zongyi/list.php?' . $opt);

            $filter_start = '<div class="s-filter">';
            $filter_end = '<div class="js-tab-container"';
            $filter_start = strpos($dom, $filter_start);
            $filter_end = strpos($dom, $filter_end, $filter_start) - 18;

            $filter_buffer = substr($dom, $filter_start, $filter_end - $filter_start);
            self::$filter = str_replace(self::host . 'zongyi/list.php', 'variety.php', $filter_buffer);

            $dom .= self::curl_get_contents(self::host . 'zongyi/list.php?' . $opt . '&pageno=2');
            $dom .= self::curl_get_contents(self::host . 'zongyi/list.php?' . $opt . '&pageno=3');

            $varietyNameDom = '/<span class="s1">[^{](.*?)<\/span>/';
            $varietyUpdateDom = '/<span class="hint">(.*?)<\/span>/';
            $varietyLinkDom = '/<a class="js-tongjic" href="(.*?)"/';
            $varietyActorDom = '/<\/span>\s+<\/p>\s+(<p class="star">[^{](.*?)<\/p>)?\s{22,}<\/div>/';
            $varietyImgDom = '/<div class="cover g-playicon">\s+<img src="(.*?)">/';

            preg_match_all($varietyNameDom, $dom, $varietyName);
            preg_match_all($varietyUpdateDom, $dom, $varietyUpdate);
            preg_match_all($varietyLinkDom, $dom, $varietyLink);
            preg_match_all($varietyActorDom, $dom, $varietyActor);
            preg_match_all($varietyImgDom, $dom, $varietyImg);

            $varieties = array();
            foreach ($varietyName[1] as $key => $value) {
                $buffer['title'] = substr($varietyName[0][$key], 17, -7);
                $buffer['tag'] = $varietyUpdate[1][$key];
                $buffer['coverpage'] = $varietyLink[1][$key];
                $buffer['desc'] = empty($varietyActor[1][$key]) ? '暂无简介' : substr($varietyActor[1][$key], 16, -4);
                $buffer['cover'] = $varietyImg[1][$key];

                $varieties[$key] = $buffer;
            }

            $temp->save(['filter' => self::$filter, 'list' => $varieties]);

            return $varieties;
        }
    }

    /**
     * 获取电视剧列表
     * @param $opt
     * @return array
     */
    public static function getTeleplays($opt)
    {
        $temp = new Temp('Teleplays_' . $opt);
        $res = $temp->get();

        if ($res) {
            self::$filter = $res['filter'];

            return $res['list'];
        } else {
            $dom = self::curl_get_contents(self::host . 'dianshi/list.php?' . $opt);

            $filter_start = '<div class="s-filter">';
            $filter_end = '<div class="js-tab-container"';
            $filter_start = strpos($dom, $filter_start);
            $filter_end = strpos($dom, $filter_end, $filter_start) - 18;

            $filter_buffer = substr($dom, $filter_start, $filter_end - $filter_start);
            self::$filter = str_replace(self::host . 'dianshi/list.php', 'teleplay.php', $filter_buffer);

            $dom .= self::curl_get_contents(self::host . 'dianshi/list.php?' . $opt . '&pageno=2');
            $dom .= self::curl_get_contents(self::host . 'dianshi/list.php?' . $opt . '&pageno=3');

            $tvNameDom = '/<span class="s1">[^{](.*?)<\/span>/';
            $tvUpdateDom = '/<span class="hint">(.*?)<\/span>/';
            $tvLinkDom = '/<a class="js-tongjic" href="(.*?)"/';
            $tvActorDom = '/<p class="star">主演：\s?(.*?)<\/p>/';
            $tvImgDom = '/<div class="cover g-playicon">\s+<img src="(.*?)">/';

            preg_match_all($tvNameDom, $dom, $tvName);
            preg_match_all($tvUpdateDom, $dom, $tvUpdate);
            preg_match_all($tvLinkDom, $dom, $tvLink);
            preg_match_all($tvActorDom, $dom, $tvActor);
            preg_match_all($tvImgDom, $dom, $tvImg);

            $teleplays = array();
            foreach ($tvName[1] as $key => $value) {
                $buffer['title'] = substr($tvName[0][$key], 17, -7);
                $buffer['tag'] = $tvUpdate[1][$key];
                $buffer['coverpage'] = $tvLink[1][$key];
                $buffer['desc'] = $tvActor[1][$key];
                $buffer['cover'] = $tvImg[1][$key];

                $teleplays[$key] = $buffer;
            }

            $temp->save(['filter' => self::$filter, 'list' => $teleplays]);

            return $teleplays;
        }
    }

    /**
     * 获取动漫列表
     * @param $opt
     * @return array
     */
    public static function getAnimes($opt)
    {
        $temp = new Temp('Animes_' . $opt);
        $res = $temp->get();

        if ($res) {
            self::$filter = $res['filter'];

            return $res['list'];
        } else {

            $dom = self::curl_get_contents(self::host . 'dongman/list.php?' . $opt);

            $filter_start = '<div class="s-filter">';
            $filter_end = '<div class="js-tab-container"';
            $filter_start = strpos($dom, $filter_start);
            $filter_end = strpos($dom, $filter_end, $filter_start) - 18;

            $filter_buffer = substr($dom, $filter_start, $filter_end - $filter_start);
            self::$filter = str_replace(self::host . 'dongman/list.php', 'anime.php', $filter_buffer);

            $dom .= self::curl_get_contents(self::host . 'dongman/list.php?' . $opt . '&pageno=2');

            $dom = str_replace('<span class="s1">{if src}{src}{else}为您推荐{/if}</span>', '', $dom);

            $animeNameDom = '/<span class="s1">(.*?)<\/span>/';
            $animeUpdateDom = '/<span class="hint">(.*?)<\/span>/';
            $animeLinkDom = '/<a class="js-tongjic" href="(.*?)"/';
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

            $temp->save(['filter' => self::$filter, 'list' => $animes]);

            return $animes;
        }
    }

    /**
     * 关键字搜索
     * @param $kw
     * @return array
     */
    public static function search($kw)
    {
        if (empty($kw)) {
            $dom = self::curl_get_contents(self::host . 'dianying/list.php?year=all&area=all&act=all&cat=all');
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
        $descDom = '/<i>简&nbsp;&nbsp;介&nbsp;：<\/i>\s*(<p>)?(.*?)<\/p>/';

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
                $buffer['img'] = '';
            }

            if (!empty($score[1][$key])) {
                $buffer['score'] = $score[1][$key];
            } else {
                $buffer['score'] = '无';
            }

            if (isset($desc[2][$key])) {
                $buffer['desc'] = $desc[2][$key];
            } else {
                $buffer['desc'] = '无';
            }

            $buffer['link'] = substr($link[1][$key], 21);

            $search[$key] = $buffer;
        }

        return $search;
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

    public static function recordSearch($hotWord, $list)
    {
        $jsonHotSearch = self::saveInfo('search');

        if (!empty($jsonHotSearch)) {
            $arrHotSearch = json_decode($jsonHotSearch, true);  //解析为数组格式
            if (array_key_exists($hotWord, $arrHotSearch)) { //有记录则加一
                $arrHotSearch[$hotWord] += 1;

                if ($arrHotSearch[$hotWord] == max($arrHotSearch)) {    //搜索最多的作为默认列表
                    self::saveInfo('search_d', $list);
                }
            } else {  //无记录则在数组中创建
                $arrHotSearch[$hotWord] = 1;
            }

            $jsonHotSearch = json_encode($arrHotSearch);
        } else {  //文件为空
            $arrHotSearch = [$hotWord => 1];
            $jsonHotSearch = json_encode($arrHotSearch);
            self::saveInfo('search_d', $list);
        }

        self::saveInfo('search', $jsonHotSearch);
    }

    public static function saveInfo($dir, $new = '')
    {
        $filePath = 'Cinema/' . $dir . '.json';

        if (!is_file($filePath)) {
            file_put_contents($filePath, '');
        }

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

    public static function getHistory($max = 10, $dir = 'search')
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
