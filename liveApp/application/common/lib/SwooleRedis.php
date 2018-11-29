<?php

namespace app\common\lib;
use Swoole\Coroutine\Redis;

class SwooleRedis {
    private $redis = null;
    private $config = [];

    public function __construct(array $config = [])
    {
        $this->config = $config ? $config : config('redis');
        if (!$this->config) {
            exception('need redis connect config');
        }

        $this->redis = new Redis();
        if (!$this->redis->connect($this->config['host'], $this->config['port'])) {
            exception('redis connect failed');
        }
    }

    /**
     * 设置value
     * @param $k
     * @param $v
     * @param int $timeout
     */
    public function setVal($k, $v, $timeout = 0)
    {
        $this->redis->set($k, $v, $timeout);
    }

    /**
     * @param $k
     * @return array|mixed
     */
    public function gettVal($k)
    {
        return $this->redis->get($k);
    }

    public function getSmsKey($k)
    {
        return 'sms_' . $k;
    }
}