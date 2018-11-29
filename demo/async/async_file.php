<?php

/*
 * 异步读取文件的步骤：
 * 1、检查文件是否存在，能成功打开立即返回true
 * 2、执行后续代码
 * 3、数据读取完毕，执行回调函数
 */

//一次性读取全部内容，最大可读取 4M 的文件。
$res = swoole_async_readfile('./test.log', function ($filename, $content) {
    echo "start read file:" . PHP_EOL;
    echo $filename . " content: " . $content;
});

var_dump($res);//文件不存在会返回false；成功打开文件立即返回true


//分块读取文件，用于大文件读取
$res = swoole_async_read('./test.log', function ($filename, $content) {
    echo $content .  PHP_EOL;
    return false;//返回false时，停止读取
}, 4, 2);

//异步写文件，一次写全部.，当写入完成时，自动回调指定的函数
$res = swoole_async_writefile('./test1.log', "hello swoole" , function ($filename) {
    echo "文件【{$filename}】写入完成" . PHP_EOL;
}, FILE_APPEND);
var_dump($res);//打开文件失败时返回false，比如不具备指定文件的写权限


//异步写文件，分段写，减少内存占用 , -1标识从文件末尾开始写入
swoole_async_write('./test.log', PHP_EOL .'hello by swoole_async_write', -1, function () {
    echo '异步分段写入完成' . PHP_EOL;
});