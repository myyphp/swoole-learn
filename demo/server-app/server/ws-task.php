<?php

class Ws {
    protected $ws;
    const HOST = '0.0.0.0';
    const PORT = 9501;
    protected $connected_fd = [];//已经连接的用户

    public function __construct()
    {
        $this->ws = new swoole_websocket_server(self::HOST, self::PORT);
        $this->setConf([
            'task_worker_num' => 2,//Task进程的数量,配置此参数后将会启用task功能。所以Server务必要注册onTask、onFinish2个事件回调函数。
        ]);

        /*
         * 投递一个异步任务到task_worker池中。此函数是非阻塞的，执行完毕会立即返回。
         * Worker进程可以继续处理新的请求。
         * 使用Task功能，必须先设置 task_worker_num，并且必须设置Server的onTask和onFinish事件回调函数。
         */

        $this->ws->on('open', [$this, 'onOpen']);
        $this->ws->on('message', [$this, 'onMessage']);
        $this->ws->on('close', [$this, 'onClose']);

        /* 如果没有注册这两个事件，服务器程序将无法启动 */
        $this->ws->on('task', [$this, 'onTask']);
        $this->ws->on('finish', [$this, 'onFinish']);

    }

    public function setConf($conf = [])
    {
        $conf && $this->ws->set($conf);
        return $this;
    }

    public function onOpen(swoole_websocket_server $server, swoole_http_request $request)
    {
        $this->mlog("客户端【{$request->fd}】连接成功");
        $this->connected_fd[$request->fd] = 1;
        $server->push($request->fd, "欢迎您！");
    }

    public function onMessage(swoole_websocket_server $server, swoole_websocket_frame $frame)
    {
        $this->mlog("收到来自客户端[{$frame->fd}]的消息：{$frame->data}, opcode:{$frame->opcode}, finish:{$frame->finish}");
        /* 使用task处理耗时任务 */
        $server->task(['title' => '待付款订单', 'content' => '您有一个待付款的订单需要处理，请查看...']);

        $server->push($frame->fd, "收到！");
    }

    public function onClose(swoole_websocket_server $server, $fd)
    {
        $this->mlog("客户端【{$fd}】连接断开!");
        unset($this->connected_fd[$fd]);
    }

    /**
     *  worker进程可以使用swoole_server_task函数向task_worker进程投递新的任务。
     *  当前的Task进程在调用onTask回调函数时会将进程状态切换为忙碌，这时将不再接收新的Task，
     *  当onTask函数返回时会将进程状态切换为空闲然后继续接收新的Task。
     *
     *  函数执行时遇到致命错误退出，或者被外部进程强制kill，当前的任务会被丢弃，但不会影响其他正在排队的Task
     *
     * @param swoole_server $serv
     * @param int $task_id 任务ID，当前进程内的唯一，但是不同进程可能重复
     * @param int $src_worker_id 来自于哪个worker进程，和 $task_id 组合起来才具备全局唯一性
     * @param $data 任务内容
     * @return mixed
     */
    public function onTask(swoole_server $serv, int $task_id, int $src_worker_id, $data)
    {
        $content = json_encode($data, JSON_UNESCAPED_UNICODE);
        $this->mlog("任务【{$task_id}，内容：{$content}】开始执行...");
        //模拟耗时任务
        sleep(10);
        return 'end';
    }

    /**
     * 当worker进程投递的任务在task_worker中完成时，task进程会通过 swoole_server->finish() 方法将任务处理的结果发送给worker进程。
     *
     * @param swoole_server $serv
     * @param int $task_id
     * @param string $data 任务处理的结果内容,也就是 onTask 返回的内容
     */
    public function onFinish(swoole_server $serv, int $task_id, string $data)
    {
        $this->mlog("任务执行结束，结果：{$data}");
        return;
    }



    public function start()
    {
        $this->ws->start();
    }

    public function mlog($msg)
    {
        echo $msg . "\n";
    }
}

$ws = new Ws();
$ws->start();