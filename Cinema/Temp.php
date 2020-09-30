<?php

namespace Cinema;

/**
 * Class Temp
 * @package Cinema
 */
class Temp
{
    /**
     * @var int
     */
    private $time;
    private $key;

    /**
     * Temp constructor.
     */
    public function __construct($key)
    {
        if (!is_dir('./Cinema/temp')) {
            mkdir('./Cinema/temp', 777, true);
        }

        $this->key = $key;
        $this->time = time();
    }

    public function filename()
    {
        return './Cinema/temp/' . $this->key . '.json';
    }

    /**
     * @return bool|mixed
     */
    public function get()
    {
        if (is_file($this->filename())) {
            $res = file_get_contents($this->filename());
            if (empty($res)) {
                $this->del();

                return false;
            }

            $res = json_decode($res, true);
            if ($res['keep'] > $this->time) {
                return $res['obj'];
            } else {
                $this->del();
            }
        }
        return false;
    }

    /**
     * @param $obj
     * @param int $keep
     * @return false|int number of written
     */
    public function save($obj, $keep = 86400)
    {
        $res = ['keep' => $this->time + $keep, 'obj' => $obj];

        return file_put_contents($this->filename(), json_encode($res));
    }

    /**
     * @return bool success or fail
     */
    private function del()
    {
        return unlink($this->filename());
    }
}