<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/8
 * Time: 17:41
 */

$server = new swoole_server('127.0.0.1', 9501, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);

$server->set([
    'worker_num' => 2,
    'max_request' => 100,
]);

/*
 * 监听收到包的事件：onPacket
 * 获取客户端的信息：$client_info 里面包含了：address(ip)、port、server_socket
 */
$server->on('Packet', function (swoole_server $server, $data, $client_info) {
    //$fd = unpack('L', pack('N', ip2long($client_info['address'])))[1];
    echo date('Y-m-d H:i:s') . " 收到来自 {$client_info['address']} - {$client_info['port']} 的数据：{$data}\n";
    $server->sendto($client_info['address'], $client_info['port'], '你好！我已收到您的信息！');
});



$server->start();
