<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/9
 * Time: 14:18
 */

$ws = new swoole_websocket_server('0.0.0.0', 9501);

//打开事件
$ws->on('open', function (swoole_websocket_server $server, swoole_http_request $request) {
    echo "server:客户端【{$request->fd}】连接成功\n";
    $server->push($request->fd, "hello,man!\n");
});

//客户端发来消息时触发
$ws->on('message', function (swoole_websocket_server $server, swoole_websocket_frame $frame) {
    echo "收到来自客户端[{$frame->fd}]的消息：{$frame->data}, opcode:{$frame->opcode}, finish:{$frame->finish}\n";

    /* 模拟耗时响应 */
    sleep(5);

    $server->push($frame->fd, "this is server");
});

$ws->on('close', function (swoole_websocket_server $server, $fd) {
    echo "客户端【{$fd}】 关闭\n";
});

$ws->start();