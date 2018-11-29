<?php

$http = new swoole_http_server('0.0.0.0', 80);

$http->set([
    'enable_static_handler' => true,
    'document_root' => '/vagrant/app/swoole-learn/liveApp/public/static/',
    'worker_number' => 5,
]);

/* 当Worker进程/Task进程启动时触发 */
$http->on('WorkerStart', function (swoole_server $server, $worker_id) {
    // 定义TP应用目录
    define('APP_PATH', __DIR__ . '/../application/');
    //从tp的start.php中copy过来
    require __DIR__ . '/../thinkphp/base.php';
});

$http->on('request', function (swoole_http_request $request, swoole_http_response $response) use ($http) {
    /* 由于swoole的 $_SERVER $_GET $_POST $_COOKIE等 tp自身的都不一样，因此，需要讲这些数据转换成原生的 */
    //ThinkPHP Request对象是从PHP系统超全局数组 $_SERVER/$_GET/$_POST/$_SESSION 中获取访问信息，所以需要对这些数组进行初始化
    //因为进程会常驻在内存中，所以在一次请求结束后相关的信息不会被销毁，因此需要在赋值手动清空
    $_SERVER = $_GET = $_POST = $_COOKIE = [];
    if (isset($request->server)) {
        foreach ($request->server as $k_server => $v_server) {
            $_SERVER[strtoupper($k_server)] = $v_server;
        }
    }

    if (isset($request->header)) {
        foreach ($request->header as $k_header => $v_header) {
            $_SERVER[strtoupper($k_header)] = $v_header;
        }
    }

    if (isset($request->get)) {
        foreach ($request->get as $k_get => $v_get) {
            $_GET[$k_get] = $v_get;
        }
    }

    if (isset($request->post)) {
        foreach ($request->post as $k_post => $v_post) {
            $_POST[$k_post] = $v_post;
        }
    }

    if (isset($request->cookie)) {
        foreach ($request->cookie as $k_cookie => $v_cookie) {
            $_COOKIE[$k_cookie] = $v_cookie;
        }
    }

    //需要将内容通过 swoole 的end（）方法输出，因此需要截取tp的输出内容，在方法里面使用 echo 代替 return
    ob_start();
    //执行应用
    try {
        //执行的方法里面的值需要使用 return 返回，才能在ob中获取到
        think\Container::get('app', [APP_PATH])->run()->send();
    } catch (think\Exception $e) {

    }

    if ($res = ob_get_contents()) {
        ob_end_clean();
    }

    $response->end($res);

    //需要注销掉本次请求的信息，不然下次请求还是会使用本次的信息
    //$http->close();//关闭连接，此种方式相当于会关闭进程，然后swoole会重新启动一个新的进程，因此不可取
    //最终采用的方式：修改tp源码：think\Request\pathinfo()   think\Request\path() ，注释掉缓存，每次拿实时的
});


$http->start();