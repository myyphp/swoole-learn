<?php

$urls = [
    'http://utiliti.com.cn/',
    'http://utiliti.com.cn/',
    'http://utiliti.com.cn/',
    'http://utiliti.com.cn/',
];

//传统方式
$start = time();
foreach ($urls as $v) {
    file_get_contents($v);
    //假设每个请求至少需要1s
    sleep(1);
}
$end = time();

echo "It takes ".($end - $start)." seconds by foreach" . PHP_EOL;//4s

//多进程方式：
$start = time();
foreach ($urls as $v) {
    (new swoole_process(function(swoole_process $pro) use ($v) {
        file_get_contents($v);
        //假设每个请求至少需要1s
        sleep(1);
    }))->start();
}
$end = time();

echo "It takes ".($end - $start)." seconds by processes" . PHP_EOL;//不到1s