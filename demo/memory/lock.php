<?php

/*
 * 1.6.4版本增加了锁的实现
 * 支持5种类型的锁：
 *  1、文件锁 SWOOLE_FILELOCK
 *  2、读写锁 SWOOLE_RWLOCK
 *  3、信号量 SWOOLE_SEM
 *  4、互斥锁 SWOOLE_MUTEX
 *  5、自旋锁 SWOOLE_SPINLOCK
 *
 * 注意：请勿在onReceive等回调函数中创建锁，否则底层的GlobalMemory内存会持续增长，造成内存泄漏。
 */
$lock = new swoole_lock(SWOOLE_MUTEX);
echo "[Master]create lock\n";
$lock->lock();
if (pcntl_fork() > 0)
{
    sleep(1);
    $lock->unlock();
}
else
{
    echo "[Child] Wait Lock\n";
    $lock->lock();
    echo "[Child] Get Lock\n";
    $lock->unlock();
    exit("[Child] exit\n");
}
echo "[Master]release lock\n";
unset($lock);
sleep(1);
echo "[Master]exit\n";
