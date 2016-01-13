<?php

namespace Lib;


class Dispacher {

    private $path;
    private $request;

    public static function get() {
        static $single = NULL;
        is_null($single) && $single = new self();
        return $single;
    }

    private function __CONSTRUCT() {
        $this->path = strtok($_SERVER['REQUEST_URI'], '?');
        $this->request['GET'] = $_GET;
        $this->request['POST'] = $_POST;
        $this->request['REQUEST'] = $_REQUEST;
    }

    public function dispach() {
        $piece = array_filter(explode('/', $this->path));
        $class = array_shift($piece);

        $controller = '\\App\\Controller\\' . ucfirst($class);

        $obj = new $controller($this->request);

        $obj->control();
    }
}