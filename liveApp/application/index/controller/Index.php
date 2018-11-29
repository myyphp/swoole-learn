<?php
namespace app\index\controller;

use think\Controller;
use app\common\lib\ali\Sms;
use app\common\lib\SwooleRedis;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch(APP_PATH . '/../public/static/live/index.html');
    }

    public function login()
    {
        if ($this->request->isAjax()) {
            $code = $_GET['code'];
            //从redis中拿出code比对
        } else {
            return $this->fetch(APP_PATH . '/../public/static/live/login.html');
        }

    }

    /**
     * 获取手机验证码
     */
    public function code()
    {
        $phone_num = $_GET['phone_num'];
        if (!$phone_num) {
            return $this->ajaxReturnError('请输入手机号码！');
        }

        if (!is_mobile_phone($phone_num)) {
            return $this->ajaxReturnError('请输入正确的号码！');
        }

        if ($phone_num == '13138638542') {
            $code = random_int(1000, 9999);
            $res = Sms::sendSms($phone_num, 'UT资源管理系统', 'SMS_151910400', ['code' => $code]);
            //var_dump($res);
            if ($res->Code == 'OK') {
                //发送成功
                //写入redis记录
                go(function () use ($code, $phone_num) {
                    $redis = new \Swoole\Coroutine\Redis();
                    $redis->connect('127.0.0.1', 6379);
                    $redis->set('sms_' . $phone_num,$code, 120);
                });
                return $this->ajaxReturnSuccess('发送成功！');
            } else {
                //发送异常
                return $this->ajaxReturnError('发送失败！');
            }
        }
    }

    protected function ajaxReturnSuccess($msg)
    {
        return json_encode([
            'msg' => $msg,
            'status' => 'ok'
        ], JSON_UNESCAPED_UNICODE);
    }

    protected function ajaxReturnError($msg)
    {
        return json_encode([
            'msg' => $msg,
            'status' => 'error'
        ], JSON_UNESCAPED_UNICODE);
    }



    /**
     * 定义空操作
     *
     * @return string
     */
    public function _empty()
    {
        return $this->index();
    }
}
