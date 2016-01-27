<?php

namespace Lib\Queue\Core;


use Lib\Db\Redis;

class QueueRedis {

    private $handle;
    public $data;

    public function connect() {
        $redis = new Redis();
        $this->handle = $redis;
    }

    public function add($queue_name, $value) {
        try {
            $queue_key = 'queue_' . $queue_name;
            $res = $this->handle->lPush($queue_key, json_encode($value));
        } catch (\RedisException $e) {
            $res = NULL;
        }

        return !empty($res) ? true : false;
    }

    public function get($queue_name) {
        $queue_key = 'queue_' . $queue_name;
        try {
            $this->data = $this->handle->rPop($queue_key);
        } catch (\RedisException $e) {
            $this->data = NULL;
        }

        return $this->data;
    }
} 