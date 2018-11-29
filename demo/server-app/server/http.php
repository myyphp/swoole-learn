<?php

$http = new swoole_http_server('0.0.0.0', 9500);

/*$http->set([
    'document_root' => '/vagrant/app/swoole-learn/server-app/htdocs/',
    'enable_static_handler' => true,
]);*/

$http->on('request', function (swoole_http_request $request, swoole_http_response $response) {
    echo "cli request.\n";
    $response->header('Content-Type', 'text/html; charset=utf-8');
    $response->header('pragma', 'no-cache');
    $response->header('cache-control', 'no-cache');
    $response->header('expires', '0');
    $response->end(file_get_contents('../htdocs/ws.html'));
    //$response->end("<h1>Hello world</h1>");
});

$http->start();