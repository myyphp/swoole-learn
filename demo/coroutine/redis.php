<?php

/*
 * 使用协程的方式操作redis
 */

//直接这么使用会报错：coroutine client should use under swoole server in onRequet, onReceive, onConnect callback
/*$redis = new Swoole\Coroutine\Redis();
$redis->connect('127.0.0.1', 6379);
$redis->set('name', 'zs');*/

//放在回调中使用
$http = new swoole_http_server('0.0.0.0', 8001);
$http->on('request', function (swoole_http_request $request, swoole_http_response $response) {
    $redis = new Swoole\Coroutine\Redis();
    $redis->connect('127.0.0.1', 6379);
    $response->end($redis->get($request->get['k']));
});

$http->start();