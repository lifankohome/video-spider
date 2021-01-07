<?php

namespace Visits;

class Visits
{
    private $id;
    private $extra;

    public function __construct($extra, $id = 'auto')
    {
        $this->extra = $extra;

        if ($id == 'auto') {
            $this->id = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/') + 1, -4);
        } else {
            $this->id = $id;
        }
    }

    public function update()
    {
        $filename = $this->extra . 'visits_' . $this->id . '.txt';
        if (!is_file($filename)) {
            file_put_contents($filename, '');
        }

        file_put_contents($filename, '*', FILE_APPEND);
        $visits = strlen(file_get_contents($filename));

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

        return '<span style="font-size: 12px;color: #afafaf"> 访客:' . $sum . '<span id="sCnt"></span></span>';
    }
}