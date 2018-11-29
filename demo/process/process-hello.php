<?php

/*
 * 第一个参数：子进程创建成功后要执行的函数
 * 第二个参数：是否重定向子进程的标准输入和输出，启用此选项后，在子进程内输出内容将不是打印屏幕，而是写入到主进程管道。读取键盘输入将变为从管道中读取数据。默认为阻塞读取
 * 第三个参数：重定向的管道类型，启用$redirect_stdin_stdout后，此选项将忽略用户参数，强制为1。 0：不创建管道；1：创建SOCK_STREAM类型管道；2：创建SOCK_DGRAM类型管道
 */
$process = new swoole_process(function (swoole_process $pro) {
    echo "创建了子进程【{$pro->pid}】" . PHP_EOL;
    $pro->write('hello swoole-process');
}, true);

$pid = $process->start();//返回的是创建的子进程的id
echo "父进程开始执行..." . PHP_EOL;
usleep(100);//需要等到子进程将内容写入管道了
echo $process->read() . PHP_EOL;
$process::wait();


/* 在子进程中开启一个server服务 */