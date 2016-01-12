<?php

namespace Lib;

class Autoloader {

    public static function get() {
        static $single = NULL;
        is_null($single) && $single = new self();
        return $single;
    }

    private function __CONSTRUCT() {
        //找不到类文件调用
        spl_autoload_register(array($this, 'autoload'));
    }

    public function autoload($class_name) {
        $path = $this->getPath($class_name);

        if (file_exists(LIB . $path)) {
            require_once(LIB . $path);
        } else if (file_exists(APP . $path)) {
            require_once(APP . $path);
        } else {
            die('404 not found');
        }
    }

    private function getPath($class_name) {
        $piece = explode('\\', $class_name);

        $class_name = array_pop($piece);

        $path = '';
        do {
            $path = strtolower(array_pop($piece)) . DIRECTORY_SEPARATOR . $path;
        } while(count($piece) > 1);

        $path = $path . ucfirst($class_name) . '.php';

        return $path;
    }
}