<?php

/*
 * swoole_table一个基于共享内存和锁实现的超高性能，并发数据结构
 *
 * 适用场景：多进程间共享数据的时候，可以使用内存表
 */

//第一个构造参数：限制内存表的总行数
$table = new swoole_table(1024);

//设置表格字段  参数 （字段名：string ， 字段类型：int、float、string ， 长度：int）
$table->column('id',$table::TYPE_INT,4);
$table->column('name',$table::TYPE_STRING,64);
$table->column('price',$table::TYPE_INT,11);

//生成内存表
$table->create();

//内存表中的数据是通过 key=>val 的形式存储的，要增加一条数据，有两种方式：
$table->set('iphoneX',[
    'id'=>1,
    'name'=>'iphoneX',
    'price'=>9999
]);

//方式2：
$table['xiaomi'] = [
    'id'=>2,
    'name'=>"小米",
    'price'=>3339
];

//将 xiaomi 的price设置为 4999
$table->set('xiaomi', ['price'=>4999]);
var_dump($table->get('xiaomi'));

//设置 iphoneX 的价格增长10
$table->incr('iphoneX', 'price', 10);
var_dump($table->get('iphoneX'));

//删除数据
$table->del('xiaomi');

var_dump($table->exist('fce'));//检测指定key是否存在

//table中存在的条目数
var_dump($table->count());

//进程执行完毕后，内存表会自动释放
