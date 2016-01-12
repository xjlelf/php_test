<?php

namespace Lib;


class Dispacher {

    private $path;

    public static function get() {
        static $single = NULL;
        is_null($single) && $single = new self();
        return $single;
    }

    private function __CONSTRUCT() {
        $this->path = $_SERVER['REQUEST_URI'];
    }

    public function dispach() {
        $piece = array_filter(explode('/', $this->path));
        $class = array_shift($piece);

        $controller = '\\App\\Controller\\' . ucfirst($class);

        $obj = new $controller();

        $obj->run();
    }
}