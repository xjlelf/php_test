<?php
define('LIB', './lib/');
define('APP', './app/');

$options = getOptions();

require_once(LIB . 'Autoloader.php');

\Lib\Autoloader::get();
ini_set('date.timezone','Asia/Shanghai');

//配置文件路径设置
\Lib\Config::setConfigNamespace('\\App\\Config');

function debugLog($str) {
    echo '['.date('Y-m-d H:i:s').'] '.$str."\n";
}

function getOptions() {
    global $argv;
    array_shift($argv);

    $options = array();
    foreach ($argv as $arg) {
        if (strpos($arg, '--') !== 0) {
            continue;
        }

        $pair = str_replace('--', '', $arg);

        if (strpos($pair, '=') !== false) {
            list($key, $value) = explode('=', $pair);
        } else {
            $key = $pair;
            $value = false;
        }

        $key = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', $key));

        $options[$key] = $value;
    }

    return $options;
}

/**
 * 摆脱终端，使进程成为新的会话组长
 */
function daemon() {
    $pid = pcntl_fork();
    if ($pid == - 1) {
        die ( "fork failed for daemon" );
    } else if ($pid) {
        exit();
    } else {
        //摆脱终端，使进程成为新的会话组长
        if (posix_setsid() == - 1) {
            die ("could not detach from terminal");
        }
    }
}