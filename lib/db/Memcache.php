<?php

namespace Lib\Db;


class Memcache extends \Memcache {

    protected $config;
    protected static $con_name = 'default';

    public static function getinstance($con_name = null) {
        static $con = NULL;

        if (empty($con_name)) {
            $con_name = self::$con_name;
        }

        if (is_null($con[$con_name])) {
            $con[$con_name] = new self($con_name);
        }

        return $con[$con_name];
    }

    private function __CONSTRUCT($con_name) {
        $this->config = \Lib\Config::load('Memcache')->$con_name;

        $this->connect($this->config['host'], $this->config['port']);
    }

    public function __DESTRUCT() {
        $this->close();
    }
} 