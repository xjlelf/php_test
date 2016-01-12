<?php

define('LIB', './lib/');
define('APP', './app/');

if (!function_exists('pr')) {
    function pr($data) {
        echo '<pre>';
        print_r($data);
    }
}

require_once(LIB . 'Autoloader.php');

\Lib\Autoloader::get();

require_once(LIB . 'Dispacher.php');

\Lib\Dispacher::get()->dispach();