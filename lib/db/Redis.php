<?php

namespace Lib\Db;


class Redis extends \Redis {

    protected $config;
    protected static $con_name = 'default';

    public function __CONSTRUCT($con_name = null) {

        if (empty($con_name)) {
            $con_name = self::$con_name;
        }

        $this->config = \Lib\Config::load('Redis')->$con_name;
        $this->connect($this->config['host'], $this->config['port']);
    }

    public function __DESTRUCT() {
        $this->close();
    }
} 