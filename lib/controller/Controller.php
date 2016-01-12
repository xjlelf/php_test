<?php

namespace Lib\Controller;


abstract class Controller {

    abstract public function run();

    public function __CONSTRUCT() {}

    public function control() {
        $this->run();
    }
} 