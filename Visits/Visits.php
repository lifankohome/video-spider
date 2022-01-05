<?php

namespace Visits;

class Visits
{
    private string $id;
    private string $extra;

    public function __construct($extra, $id = 'auto')
    {
        $this->extra = $extra;

        if ($id == 'auto') {
            $this->id = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/') + 1, -4);
        } else {
            $this->id = $id;
        }

        // Fix ip list to show position
        $ip = $this->getIP();

        $buf = [];
        $buf['ip'] = $ip;
        $buf['time'] = date('Y-m-d H:i', time());

        if (is_file($this->extra . 'map_data.json')) {
            $data = file_get_contents($this->extra . 'map_data.json');
            if (empty($data)) {
                $data = [];
            } else {
                $data = json_decode($data, true);
            }
        } else {
            $data = [];
        }

        if (empty($data)) {
            $ips = [];
        } else {
            $ips = array_column($data, 'ip');
        }

        if (!in_array($ip, $ips)) {
            $info = file_get_contents('https://www.lifanko.cn/festival2img/ipool.php?o=get&ip=' . $ip);
            $info = json_decode($info, true);
            if ($info['success'] == '1' && $info['result']['status'] == 'OK') {
                $city = $info['result']['att'];
                if (!empty($city)) {
                    $buf['city'] = $city;

                    $city = explode(',', $city);
                    $city = end($city);

                    $api = 'https://api.map.baidu.com/geocoder?output=json&address=' . $city . '&city=' . $city;
                    $con = curl_init($api);
                    curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($con, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($con, CURLOPT_TIMEOUT, 2);
                    $res = curl_exec($con);
                    curl_close($con);

                    $res = json_decode($res, true);
                    if ($res['status'] == 'OK') {
                        $buf['lat'] = $res['result']['location']['lat'];
                        $buf['long'] = $res['result']['location']['lng'];

                        array_push($data, $buf);

                        file_put_contents($this->extra . 'map_data.json', json_encode($data));
                    }
                }
            }
        }
    }

    private function getIP()
    {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
            $ip = getenv("REMOTE_ADDR");
        } else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = 0;
        }
        return $ip;
    }

    public function update()
    {
        $filename = $this->extra . 'visits_' . $this->id . '.txt';
        if (!is_file($filename)) {
            file_put_contents($filename, '');
        }

        $str_lack = '';
        for ($i = count(file($filename)); $i <= intval(date('H')); $i++) {
            $leader = range('A', 'Z')[$i];
            $str_lack .= ($i == 0 ? '' : "\n") . $leader;
        }

        file_put_contents($filename, $str_lack . '*', FILE_APPEND);
        $visits = strlen(file_get_contents($filename)) - 2 * $i + 1;

        $filename_history = $this->extra . 'visits_' . $this->id . '_history.json';
        if (!is_file($filename_history)) {
            file_put_contents($filename_history, '{}');
        }
        $visits_history = json_decode(file_get_contents($filename_history), true);
        $date = date('Y-m-d', time());

        if (!isset($visits_history[$date])) {
            if (count($visits_history) > 0) {
                if (end($visits_history) == '?') {
                    $visits_history[key($visits_history)] = $visits;
                }
            }

            $visits_history[$date] = '?';
            file_put_contents($filename_history, json_encode($visits_history));
            file_put_contents($filename, '');

            $sum = 0;
        } else {
            $sum = $visits;
        }

        $visits_history = array_values($visits_history);
        foreach ($visits_history as $val) {
            if (is_numeric($val)) {
                $sum += $val;
            }
        }

        $sum += 99000000;
        return '<span style="font-size: 12px;color: #afafaf"> 访客:' . number_format($sum) . '<span id="sCnt"></span></span>';
    }
}