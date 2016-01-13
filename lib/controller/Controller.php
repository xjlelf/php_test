<?php

namespace Lib\Controller;


abstract class Controller {

    protected $request;

    abstract public function run();

    public function __CONSTRUCT($request) {
        $this->request = $request;
    }

    public function control() {
        $this->run();
    }

    public function echoView($data) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data);
    }
} 