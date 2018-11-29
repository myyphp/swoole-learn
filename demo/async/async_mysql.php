<?php

$db = new swoole_mysql();
$server_cfg = [
    'host' => '192.168.33.12',
    'port' => 3306,
    'user' => 'root',
    'password' => '123456',
    'database' => 'test',
    'charset' => 'utf8', //指定字符集
    'timeout' => 2,  // 可选：连接超时时间（非查询超时时间），默认为SW_MYSQL_CONNECT_TIMEOUT（1.0）
];

$db->connect($server_cfg, function (swoole_mysql $db, $res) {
    if ($res === false) {
        //数据库连接失败，获取具体的错误信息
        var_dump($db->connect_errno, $db->connect_error);
        exit;
    }

    $sql = "SELECT * FROM user_info WHERE id = 1";
    $db->query($sql, function (swoole_mysql $db, $res) {
        if ($res === false) {
            var_dump($db->connect_errno, $db->connect_error);
            exit;
        }
        elseif ($res === true) {//add update delete操作
            var_dump($db->affected_rows, $db->insert_id);
        }
        var_dump($res);//res返回的是查询的结果内容
        //查询完毕后记得关闭
        $db->close();
    });
});