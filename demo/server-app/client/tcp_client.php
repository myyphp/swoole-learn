<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/8
 * Time: 17:21
 */

$cli = new swoole_client(SWOOLE_SOCK_TCP);

if (!$cli->connect('127.0.0.1', 9501)) {
    exit('连接失败');
}

//php内置了 cli常量
//往cli页面输出内容
fwrite(STDOUT, "请输入消息内容：\n");
//接收用户录入的内容
$msg = trim(fgets(STDIN));

//发送数据到服务端
if (!$cli->send($msg)) {
    echo "消息({$msg})发送失败";
}

//接收服务端发送过来的数据
$receive = $cli->recv();

echo "接收到服务端的数据：{$receive}";