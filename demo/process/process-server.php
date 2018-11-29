<?php

$process = new swoole_process(function (swoole_process $pro) {
    //在子进程中开启一个外部应用程序
    $pro->exec("/usr/local/php/bin/php", [__DIR__ . '/../server-app/server/http.php']);//绝对路径
});

$pid = $process->start();
echo "子进程ID：" . $pid . PHP_EOL;

$process::wait();