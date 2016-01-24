<?php

error_reporting(E_ALL^(E_NOTICE));

define('LIB', './lib/');
define('APP', './app/');

if (!function_exists('pr')) {
    function pr($data) {
        echo '<pre>';
        print_r($data);
    }
}

ini_set('date.timezone','Asia/Shanghai');

require_once(LIB . 'Autoloader.php');

\Lib\Autoloader::get();

//配置文件路径设置
\Lib\Config::setConfigNamespace('\\App\\Config');

require_once(LIB . 'Dispacher.php');

\Lib\Dispacher::get()->dispach();

//register_shutdown_function(array(\Lib\Shut::instance(), 'shut'));