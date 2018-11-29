<?php

/*
 * 设置一个定时器，指定毫秒数后执行回调函数
 */
$str = 'timer1';
swoole_timer_after(3000, function () use ($str) {
    echo "Timeout: {$str}\n";
});

/*
 * 设置一个定时器，每隔 1s 执行一次，直到3次后取消定时器
 */
$str = 'timer2';
$c = 1;
swoole_timer_tick(1000, function ($timer_id) use ($str) {
    global $c;
    echo "Timeout {$c}: {$str}\n";
    $c++;
    if ($c > 3) {
        swoole_timer_clear($timer_id);
    }
});


