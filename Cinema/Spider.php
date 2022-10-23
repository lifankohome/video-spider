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
    const host = 'https://api.web.360kan.com';
    const callback = '__jp0';

    private static $slider = null;
    private static $rank = null;
    public static $filter = null;

    public function __construct()
    {

    }

    private static function is_block($kw)
    {
        $filePath = 'Cinema/block.json';

        if (!is_file($filePath)) {
            file_put_contents($filePath, '{}');
        }

        $block = json_decode(file_get_contents($filePath), true);

        if (in_array('block_' . $kw, $block)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取轮播图
     * @param string $type
     * @return array
     */
    public static function getSlider($type = 'movie')
    {
        $temp = new Temp('Slider_' . $type);
        $res = $temp->get();

        if ($res) {
            self::$slider = $res;
        } else {
            $url = '';
            if ($type == 'movie') {
                $url = self::host . '/v1/block?blockid=99&callback=' . self::callback;
            }
            if ($type == 'teleplay') {
                $url = self::host . '/v1/block?blockid=503&callback=' . self::callback;
            }
            if ($type == 'variety') {
                $url = self::host . '/v1/block?blockid=227&callback=' . self::callback;
            }
            if ($type == 'anime') {
                $url = self::host . '/v1/block?blockid=79&callback=' . self::callback;
            }

            $dom = self::curl_get_contents($url);

            $callback_len = strlen(self::callback);
            if (substr($dom, 0, $callback_len) == self::callback) {
                $dom = substr($dom, $callback_len + 1, -2);
            }

            $res = json_decode($dom);
            if ($res->errno != 0) {
                return [0, $res->msg];
            }

            $type = substr($type, 0, 1);

            $data = "<ul id='nav'></ul><ul>";
            foreach ($res->data->lists as $item) {
                $data .= "<li><a href='play.php?play={$type}{$item->ent_id}.html'><span class='js-slide-img' style='background-image: url({$item->pic_lists[0]->url})'></span></a></li>";
            }
            $data .= "</ul>";

            return [1, $data];
        }

        return self::$slider;
    }

    /**
     * 获取排行榜
     * @return array
     */
    public static function getRank($type)
    {
        $temp = new Temp('Rank_' . $type);
        $res = $temp->get();

        if ($res) {
            self::$rank = $res;
        } else {
            $url = '';
            $callback_rank = '';

            if ($type == 'movie') {
                $callback_rank = '__jp4';
                $url = self::host . '/v1/rank?cat=2&callback=' . $callback_rank;
            } else if ($type == 'variety') {
                $callback_rank = '__jp5';
                $url = self::host . '/v1/rank?cat=4&callback=' . $callback_rank;
            } else if ($type == 'teleplay') {
                $callback_rank = '__jp5';
                $url = self::host . '/v1/rank?cat=3&callback=' . $callback_rank;
            } else if ($type == 'anime') {
                $callback_rank = '__jp7';
                $url = self::host . '/v1/rank?cat=5&callback=' . $callback_rank;
            }

            $dom = self::curl_get_contents($url);

            if ($callback_rank != '') {
                $callback_len = strlen($callback_rank);
                if (substr($dom, 0, $callback_len) == $callback_rank) {
                    $dom = substr($dom, $callback_len + 1, -2);
                }

                $res = json_decode($dom);
                if ($res->errno != 0) {
                    return [0, $res->msg];
                }

                self::$rank = $res->data;
            } else {
                self::$rank = $dom;
            }

            $temp->save(self::$rank);
            self::$rank = $temp->get();
        }

        $buffer = [];
        $n = 0;
        foreach (self::$rank as $item) {
            if (self::is_block($item['title'])) {
                continue;
            }

            $n++;

            $item['title'] = ($n < 10 ? '0' . $n : $n) . '、' . $item['title'];
            array_push($buffer, $item);

            if ($n == 18) {
                break;
            }
        }

        return [1, $buffer];
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
            $dom = self::curl_get_contents(self::host . '/v1/filter/list?catid=1&rank=rankhot&cat=&year=&area=&act=&size=35&callback=' . self::callback);

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
                    'link' => 'm' . $value->id . '.html',
                    'desc' => mb_substr(implode(', ', $value->actor), 0, 29),
                    'cover' => $value->cdncover,
                ];
                array_push($movies, $buffer);
            }

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
            $dom = self::curl_get_contents(self::host . '/v1/filter/list?catid=3&rank=ranklatest&cat=&act=&area=&size=35&callback=' . self::callback);

            $callback_len = strlen(self::callback);
            if (substr($dom, 0, $callback_len) == self::callback) {
                $dom = substr($dom, $callback_len + 1, -2);
            }

            $res = json_decode($dom);
            if ($res->errno != 0) {
                return [0, $res->msg];
            }

            $data = $res->data;
            $varieties = array();
            foreach ($data->movies as $value) {
                $buffer = [
                    'title' => $value->title,
                    'tag' => $value->tag,
                    'link' => 'v' . $value->id . '.html',
                    'desc' => $value->lasttitle,
                    'cover' => $value->cdncover,
                ];
                array_push($varieties, $buffer);
            }

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
            $dom = self::curl_get_contents(self::host . '/v1/filter/list?catid=2&rank=rankhot&cat=&year=&area=&act=&size=35&callback=' . self::callback);

            $callback_len = strlen(self::callback);
            if (substr($dom, 0, $callback_len) == self::callback) {
                $dom = substr($dom, $callback_len + 1, -2);
            }

            $res = json_decode($dom);
            if ($res->errno != 0) {
                return [0, $res->msg];
            }

            $data = $res->data;
            $teleplays = array();
            foreach ($data->movies as $value) {
                if (self::is_block($value->title)) {
                    continue;
                }
                $buffer = [
                    'title' => $value->title,
                    'point' => $value->comment,
                    'tag' => $value->pubdate,
                    'link' => 't' . $value->id . '.html',
                    'desc' => mb_substr(implode(', ', $value->actor), 0, 29),
                    'cover' => $value->cdncover,
                ];
                array_push($teleplays, $buffer);
            }

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
            $dom = self::curl_get_contents(self::host . '/v1/filter/list?catid=4&rank=rankhot&cat=&year=&area=&size=35&callback=' . self::callback);

            $callback_len = strlen(self::callback);
            if (substr($dom, 0, $callback_len) == self::callback) {
                $dom = substr($dom, $callback_len + 1, -2);
            }

            $res = json_decode($dom);
            if ($res->errno != 0) {
                return [0, $res->msg];
            }

            $data = $res->data;
            $animes = array();
            foreach ($data->movies as $value) {
                $buffer = [
                    'title' => $value->title,
                    'point' => $value->comment,
                    'link' => 'a' . $value->id . '.html',
                    'desc' => $value->comment ? $value->comment : '无',
                    'cover' => $value->cdncover,
                ];
                array_push($animes, $buffer);
            }

            return $animes;
        }
    }

    public static function get_play_info($play)
    {
        $type = substr($play, 0, 1);

        $allow = ['t', 'm', 'v', 'a'];
        if (!in_array($type, $allow)) {
            return [0, '资源不存在'];
        }

        $id = str_replace('.html', '', substr($play, 1));

        $cat = -1;
        if ($type == 'm') {
            $cat = 1;
        } else if ($type == 't') {
            $cat = 2;
        } else if ($type == 'v') {
            $cat = 3;
        } else if ($type == 'a') {
            $cat = 4;
        }

        $url = self::host . '/v1/detail?cat=' . $cat . '&id=' . $id . '&callback=' . self::callback;
        $dom = self::curl_get_contents($url);

        $callback_len = strlen(self::callback);
        if (substr($dom, 0, $callback_len) == self::callback) {
            $dom = substr($dom, $callback_len + 1, -2);
        }

        $res = json_decode($dom);
        if ($res->errno != 0) {
            return [0, $res->msg];
        }
        $data = $res->data;

        $lib = [
            'imgo' => 'VT粿芒 高级服务器',
            'leshi' => '史乐 高级服务器',
            'pptv' => '频石PP 高级服务器',
            'qiyi' => '频石咦器矮 高级服务器',
            'qq' => '频石寻藤 高级服务器',
            'youku' => '枯有 高级服务器',
            'xigua' => '卦兮 高级服务器',
            'douyin' => '音逗 高级服务器',
            'huanxi' => '幻溪 高级服务器',
            'm1905' => '5091 高级服务器',
            'sohu' => '壶搜 高级服务器'
        ];

        $sets = [];

        if ($type == 'm') {
            foreach ($data->playlinksdetail as $item) {
                array_push($sets, [
                    'title' => $lib[$item->site],
                    'link' => $item->default_url
                ]);
            }
        } else if ($type == 'v') {
            foreach ($data->defaultepisode as $item) {
                array_push($sets, [
                    'title' => $item->name,
                    'link' => $item->url
                ]);
            }
        } else if ($type == 't' || $type == 'a') {
            foreach ($data->allepidetail as $source => $episodes) {
                $buffer = [];
                foreach ($episodes as $item) {
                    array_push($buffer, [
                        'title' => '第 ' . $item->playlink_num . ' 集',
                        'link' => $item->url,
                        'is_vip' => $item->is_vip
                    ]);
                }

                $sets[$lib[$source]] = $buffer;
            }
        }

        $data = [
            'is_vip' => $data->vip ? true : false,
            'sets' => $sets,
            'rank' => $data->rank,
            'cover' => $data->cdncover,
            'actor' => $data->actor,
            'area' => $data->area,
            'pub' => $data->pubdate,
            'director' => $data->director,
            'title' => $data->title,
            'description' => $data->description
        ];

        if (self::is_block($data['title'])) {
            echo "<div style='height: 90vh;display: flex;flex-direction: column;align-items: center;justify-content: center;font-size: 30px;color: #F40;'>《{$data['title']}》已被站长屏蔽，无法显示！<div style='margin-top: 20px;'>请挑选其它影视观看吧，感谢您的理解~</div></div>";
            exit;
        }

        return [1, $data];
    }

    /**
     * 关键字搜索
     * @param $kw
     * @return array
     */
    public static function search($kw)
    {
        $search = [];

        if (empty($kw)) {
            return $search;
        }

        $dom = self::curl_get_contents("https://api.so.360kan.com/index?force_v=1&kw=$kw&pageno=1&v_ap=1&tab=all&cb=" . self::callback);

        $callback_len = strlen(self::callback);
        if (substr($dom, 0, $callback_len) == self::callback) {
            $dom = substr($dom, $callback_len + 1, -2);
        }

        $res = json_decode($dom);
        if ($res->code != 0) {
            return [0, $res->msg];
        }
        $data = $res->data;

        if (empty($data->longData)) {
            return $search;
        }

        foreach ($data->longData->rows as $item) {
            if (self::is_block($item->titleTxt)) {
                continue;
            }

            $total = false;
            if ($item->cat_id == 2) {
                // 电视剧
                $total = $item->coverInfo->txt;
            }

            $type = [
                'type_1' => 'm',
                'type_2' => 't',
                'type_3' => 'v',
                'type_4' => 'a',
            ];

            $index = strrpos($item->url, '/') + 1;
            $link = $type['type_' . $item->cat_id] . substr($item->url, $index);

            $description = $item->description;
            $description = mb_substr($description, 0, 200) . (mb_strlen($description) > 200 ? '...' : '');

            array_push($search, [
                'is_vip' => $item->vip ? true : false,
                'title' => $item->titleTxt,
                'director' => implode(' / ', $item->dirList),
                'actor' => implode(' / ', $item->actList),
                'description' => $description,
                'cover' => $item->cover,
                'label' => $item->cat_name . '·' . $item->year,
                'area' => implode(' / ', $item->area),
                'tag' => implode(' / ', $item->tag),
                'score' => $item->score,
                'link' => $link,
                'total' => $total
            ]);
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
        curl_setopt($ch, CURLOPT_COOKIE, '');
        $data = curl_exec($ch);
        curl_close($ch);

        return trim($data);
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
