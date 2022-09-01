<?php

namespace Cinema;

/**
 * Class Temp
 * @package Cinema
 */
class Temp
{
    private array $_config = [
        'redis' => [
            'host' => '127.0.0.1',
            'port' => 6379,
            'pass' => '',
            'expire' => 86400 / 2
        ],
        'file' => [
            'path' => './Cinema/temp/',
            'ext' => 'json',
            'expire' => 86400
        ]
    ];

    // Judge engine by this variable
    private ?\Redis $_redis = null;

    private string $_key;
    private string $_prefix = 'Video_Temp_';

    /**
     * Temp constructor.
     * @param $key
     * @param string $engine
     */
    public function __construct($key, $engine = 'file')
    {
        $this->_key = $key;

        if ($engine == 'redis') {
            if (!extension_loaded('redis')) {
                die('No Redis Extension');
            }
            $this->_redis = new \Redis();
            $this->_redis->connect($this->_config['redis']['host'], $this->_config['redis']['port']);
            if ('' != $this->_config['redis']['pass']) {
                $this->_redis->auth($this->_config['redis']['pass']);
            }
        } else {
            if (!is_dir($this->_config[$engine]['path'])) {
                mkdir($this->_config[$engine]['path'], 0777, true);
            }
        }
    }

    /**
     * Get Key Temped
     * @return bool|mixed
     */
    private function key()
    {
        if ($this->_redis) {
            return $this->_prefix . $this->_key;
        } else {
            return $this->_config['file']['path'] . $this->_prefix . $this->_key . '.' . $this->_config['file']['ext'];
        }
    }

    /**
     * Get object Temped
     * @return bool|mixed
     */
    public function get()
    {
        return false;
        if ($this->_redis) {
            $obj = $this->_redis->get($this->key());
            if ($obj) {
                $res = json_decode($obj, true);
                return $res['obj'];
            } else {
                return false;
            }
        } else {
            if (is_file($this->key())) {
                $obj = file_get_contents($this->key());
                if (empty($obj)) {
                    $this->del();
                    return false;
                }

                $res = json_decode($obj, true);
                if ($res['keep'] > time()) {
                    return $res['obj'];
                } else {
                    $this->del();
                }
            }
            return false;
        }
    }

    /**
     * Save object
     * @param $obj
     * @param bool|int $expire
     * @return false|int number of written
     */
    public function save($obj, $expire = false)
    {
        if ($expire === false) {
            $expire = $this->_config[$this->_redis ? 'redis' : 'file']['expire'];
        }

        $res = json_encode(['keep' => time() + $expire, 'obj' => $obj]);
        if ($this->_redis) {
            return (boolean)$this->_redis->setex($this->key(), $expire, $res);
        } else {
            return (boolean)file_put_contents($this->key(), $res);
        }
    }

    /**
     * Delete specific key
     * @return bool success or fail
     */
    private function del()
    {
        if ($this->_redis) {
            return (boolean)$this->_redis->del($this->key());
        } else {
            return unlink($this->key());
        }
    }

    /**
     * @param string $prefix
     */
    public function setPrefix(string $prefix): void
    {
        $this->_prefix = $prefix;
    }
}
