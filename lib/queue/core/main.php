<?php

require_once('./lib/queue/core/index.php');

set_error_handler('customError');

$queue_name = $options['name'];

GLOBAL $log_name;
$log_name = $queue_name;

setPid($queue_name);

GLOBAL $pids;
$pids = array();
while (true) {

    for ($i = 0; $i < 1; ++$i) {
        if (!empty($pids[$i]) && posix_kill($pids[$i], 0)) {
            continue;
        } else {
            $pid = pcntl_fork();

            if ($pid < 0) {
                die("ERROR:fork failed");
            } else if ($pid) {
                $pids[$i] = $pid;
                trigger_error("new pid=$pid");
            } else {
                start($queue_name);
                exit;
            }
        }
    }

    declare ( ticks = 1 );
    pcntl_signal(SIGTERM, "sig_handler");
    pcntl_signal(SIGHUP, "sig_handler");
    pcntl_signal(SIGINT, "sig_handler");

    $pid = pcntl_waitpid(-1, $status, WNOHANG);
    if ($pid <= 0) {
        // 没有子进程退出
        sleep(2);
    } else {
        trigger_error("process $pid is exit");
    }
}

function start($queue_name) {

    $class = '\\App\\Queue\\' . ucfirst($queue_name);
    if(class_exists($class)) {
        $redis = new \Lib\Queue\Core\QueueRedis();
        $redis->connect();
        $data = $redis->get($queue_name);
        if (empty($data)) {
            return false;
        }
        $data = json_decode($data, true);
        $queue = new $class($data);
        $data = $queue->run();
        trigger_error("处理结果：" . json_encode($data));
    }
    trigger_error("task complete");
}

function setPid($queue_name) {
    $pid = posix_getpid();
    $ret = file_put_contents(APP . "log/monitor/$queue_name.pid", "$pid");
    if (intval($ret) <= 0) {
        die("创建PID文件失败，请检查权限");
    }
}

function sig_handler() {
    GLOBAL $pids;
    foreach ($pids as $pid) {
        trigger_error("kill pid $pid");

        if (!posix_kill($pid, 0)) {
            trigger_error("process $pid has been exit(zombe)");
            continue;
        }

        $ret = posix_kill($pid, SIGINT);
        if ($ret) {
            $status = "";
            pcntl_waitpid($pid, $status);
            trigger_error("process $pid is exit");
        } else {
            trigger_error("send signal to process $pid failed");
        }
    }

    exit();
}

function customError($errno, $errstr, $errfile, $errline) {
    GLOBAL $log_name;
    switch ($errno) {
        default:
            $err_str = '[' . date('Y-m-d H:i:s') . '] ' . basename($errfile) . '(' . $errline . '): ' . $errstr . "\n";
            $log_path = APP . "log/queue/debug/" .  date('Y-m') . "/" . "{$log_name}_" . date('Y-m-d') . ".log";
            file_put_contents($log_path, $err_str);
            break;
    }
}