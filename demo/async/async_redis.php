<?php

$redis = new swoole_redis();


$redis->connect('127.0.0.1', 6379, function (swoole_redis $redis, $result) {
    echo "redis connect res:" . PHP_EOL;
    var_dump($result);//true
    $redis->set('name', 'myy', function (swoole_redis $redis, $result) {
        echo "redis set k-v:" . PHP_EOL;
        var_dump($result);//OK
        $redis->close();//要关闭，否则会阻塞在这里
    });

    $redis->get('name', function (swoole_redis $redis, $result) {
        echo "redis get k:" . PHP_EOL;
        var_dump($result);//myy
        $redis->close();//要关闭，否则会阻塞在这里
    });
});