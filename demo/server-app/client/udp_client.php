<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/8
 * Time: 17:41
 */

$client = new swoole_client(SWOOLE_SOCK_UDP);

$host = '127.0.0.1';
$port = 9501;
if (!$client->connect($host, $port, 10)) {
    exit('无法连接！');
}

echo date('Y-m-d H:i:s') . " 成功连接到：{$host} {$port}\n";


fwrite(STDOUT, "请说出的你的梦想:\n");

while (true) {
    $msg = trim(fgets(STDIN));

    if (!$client->send($msg)) {
        echo "消息发送失败，请稍后重试！\n";
        continue;
    }

    $receice = $client->recv();
    echo  date('Y-m-d H:i:s') . " 来自服务端：$receice\n";
}
