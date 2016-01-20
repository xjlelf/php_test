<?php

namespace App\Config;


class Memcache {

    public function __GET($name) {
        return $this->$name;
    }

    private $default = array(
        'host' => '127.0.0.1',
        'port' => '11211',
    );
} 