<?php

//创建Server对象，监听 127.0.0.1:9501端口
$serv = new swoole_server("0.0.0.0", 9501);
//通过set设置各项参数
$serv->set([
    'worker_num' => 2,//开启的worker进程数
    'max_conn' => 100,//最大允许维持的tcp连接数
    //'daemon' => 1,//守护进程化
    'max_request' => 2000,//此参数表示worker进程在处理完n次请求后结束运行
    'backlog' => 128,//设置对多同时多少个等待acept的连接
]);

/*
 * 监听连接进入事件
 * $fd 是客户端连接的唯一标识
 * 实际上还有第三个参数：$reactor_id 表示线程id
 */
$serv->on('connect', function ($serv, $fd, $reactor_id) {
    echo "Client: {$fd} - Connect. {$reactor_id}\n";
});

//监听数据接收事件
$serv->on('receive', function ($serv, $fd, $from_id, $data) {
    $serv->send($fd, "Server: {$from_id} - {$fd} ".$data);
});

//监听连接关闭事件
$serv->on('close', function ($serv, $fd) {
    echo "Client: Close.\n";
});

//启动服务器
$serv->start();