<?php

require_once('./lib/queue/core/index.php');

// 安装信号处理函数
declare (ticks = 1);
pcntl_signal(SIGTERM, "sigHandler");
pcntl_signal(SIGHUP, "sigHandler");

$queue_name = 'login';

$action = @$options['action'];

$monitor_file = APP . 'log/monitor/monitor.pid';

if ($action == 'stop') {
    $pid = file_get_contents($monitor_file);
    if ($pid && posix_kill($pid, 0)) {
        $ret = posix_kill($pid, SIGTERM);
        if ($ret) {
            file_put_contents($monitor_file, "0");
            debugLog("kill $pid success");
        }
    } else {
        debugLog("get pid from pid file faile.\nplease use ps and kill monitor");
    }
    exit ();
}

if (file_exists($monitor_file)) {
    $pid = file_get_contents($monitor_file);
    if ($pid && posix_kill($pid, 0)) {
        debugLog('monitor is alreader running...');
        exit;
    }
}

daemon();

$pid = posix_getpid();
if (!file_exists($monitor_file)) {
    mkdir(dirname($monitor_file), 0777, 1);
}
file_put_contents($monitor_file, $pid);

GLOBAL $running;
$running = true;
while ($running) {
    check($queue_name);
    sleep(1);
}

function check($queue_name) {
    $pidfile = APP . "log/monitor/$queue_name.pid";
    if (file_exists($pidfile)) {
        $pid = file_get_contents($pidfile);
//        debugLog("check {$queue_name} pid = $pid");
        if (!intval($pid) || !posix_kill($pid, 0)) {
            debugLog("{$queue_name} pid = $pid is kill");
            start($queue_name);
        }
    } else {
        start($queue_name);
    }
}

function start($queue_name) {
    debugLog("start $queue_name");
    $log_path = APP . "log/queue/debug/" .  date('Y-m') . "/";
    if (!file_exists($log_path)) {
        mkdir($log_path, 0777, 1);
    }
    $fp = popen("php ./lib/queue/core/main.php --name=$queue_name >> " . $log_path . "{$queue_name}_" . date('Y-m-d') . ".log 2>&1 &", 'r');
    pclose($fp);
}

function stop($queue_name) {
    debugLog('stop now ..');
    $pidfile = APP . "log/monitor/$queue_name.pid";
    if (file_exists($pidfile)) {
        $pid = file_get_contents($pidfile);
        if ($pid && posix_kill($pid, 0)) {
            $ret = posix_kill($pid, SIGTERM);
            if ($ret) {
                debugLog("Send stop signal to process $pid success");
                pcntl_waitpid($pid, $status);
                debugLog("process $pid is stop");
                file_put_contents($pidfile, "0");
            } else {
                debugLog("Send stop signal to $pid faild");
            }
        }
    }
}

function sigHandler($iSigno) {
    global $monitor_file;
    switch ($iSigno) {
        case SIGTERM :
            stop('login');
            file_put_contents($monitor_file, "0");
            exit ();
            break;
        case SIGHUP :
            stop('login');
            break;
        default :
            break;
    }
}