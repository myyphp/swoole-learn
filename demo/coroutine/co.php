<?php

/*
 * 协程适用于密集型IO计算。（不适用于：密集型CPU计算）
 */

/* 模拟密集型CPU计算 */
//go() 是 \Co::create() 的缩写
//\Co 是 \Swoole\Coroutine 的简写


//1、协程 vs 普通同步代码
go(function () {
    echo 'hi-go-1' . PHP_EOL;
});

echo 'hi-1' . PHP_EOL;

go(function () {
    echo 'hi-go-2' . PHP_EOL;
});
//执行结果：hi-go-1  hi-1 hi-go-2 (顺序执行)，和普通同步代码没什么区别，也没什么优势

echo '------------------------------------------------' . PHP_EOL;

//2、协程 vs 同步CPU计算阻塞代码
go(function () {
    sleep(1);
    echo 'hi-go-1' . PHP_EOL;
});

echo 'hi-1' . PHP_EOL;

go(function () {
    Co::sleep(1);
    echo 'hi-go-2' . PHP_EOL;
});
//执行结果：：hi-go-1  hi-1 hi-go-2 (顺序输出)，和普通同步代码没什么区别，也没什么优势

echo '------------------------------------------------' . PHP_EOL;

//2、协程 vs IO阻塞代码
go(function () {
    Co::sleep(2);
    echo 'hi-go-1' . PHP_EOL;
});

echo 'hi-1' . PHP_EOL;

go(function () {
    Co::sleep(1);
    echo 'hi-go-2' . PHP_EOL;
});

//执行结果：：  hi-1 hi-go-2 hi-go-1 执行过程：进程首先执行到第一个协程代码，当遇到IO阻塞时，立即返回，继续向下执行，第二个协程代码首先准备就绪，回调执行输出，最后再到第一个协程代码



