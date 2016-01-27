<?php

namespace Lib\Queue;


abstract class Queue {

    protected $data;

    public function __CONSTRUCT($data) {
        $this->data = $data;
    }

    abstract public function run();
} 