<?php

namespace App\Config;


class Mysql {

    public function __GET($db_name) {
        return $this->$db_name;
    }

    private $db_oms_main = array(
        'host' => '192.168.113.105',
        'port' => '3306',
        'user' => 'mua',
        'pass' => 'tp618!2#',
        'db' => 'db_oms_main'
    );

    private $default = array(
        'host' => '127.0.0.1',
        'port' => '3306',
        'user' => 'root',
        'pass' => '',
        'db' => 'test'
    );
} 