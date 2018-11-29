<?php
/**
 * 自定义函数
 */



/**
 * 统一定义一些系统所需的常量
 *
 * @author mayy<myyd@outlook.com>
 * @date 2017-3-8
 */
function defines()
{
    //定义网站域名
    if (!defined('SITE_DOMAIN')) {
        $site_domain = get_domain();
        define('SITE_DOMAIN', $site_domain);
    }

    // 定义当前网站url
    if (!defined('SITE_URL')) {
        define('SITE_URL', SITE_DOMAIN . DIRECTORY_SEPARATOR . PROJECT_NAME);
    }

    //public路径
    if (!defined('PUBLIC_PATH')) {
        define('PUBLIC_PATH', SITE_URL . '/public');
    }

    // 静态文件URL
    if (!defined('STATIC_PATH')) {
        define('STATIC_PATH', PUBLIC_PATH . '/static');
    }

    // js静态文件URL
    if (!defined('JS_PATH')) {
        define('JS_PATH', STATIC_PATH . '/js');
    }
    // css静态文件URL
    if (!defined('CSS_PATH')) {
        define('CSS_PATH', STATIC_PATH . '/css');
    }
}

/**
 * 获得当前网站的域名
 *
 * @author mayy<myyd@outlook.com>
 * @date 2017-3-8
 * @return string
 */
function get_domain()
{
    /* 协议 */
    $protocol = get_protocol();

    /* 域名或IP地址 */
    if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
        $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
    } elseif (isset($_SERVER['HTTP_HOST'])) {
        $host = $_SERVER['HTTP_HOST'];
    } else {
        /* 端口 */
        if (isset($_SERVER['SERVER_PORT'])) {
            $port = ':' . $_SERVER['SERVER_PORT'];

            if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol)) {
                $port = '';
            }
        } else {
            $port = '';
        }

        if (isset($_SERVER['SERVER_NAME'])) {
            $host = $_SERVER['SERVER_NAME'] . $port;
        } elseif (isset($_SERVER['SERVER_ADDR'])) {
            $host = $_SERVER['SERVER_ADDR'] . $port;
        }
    }

    return $protocol . $host;
}

/**
 * 获得协议类型，独立出来，预留以后修改成其他协议，比如https
 *
 * @author mayy<myyd@outlook.com>
 * @date 2017-3-8
 * @return string
 */
function get_protocol()
{
    $protocol = 'http://';
    return $protocol;
}


/**
 * 为SQL查询创建LIMIT条件
 *
 * @author mayy<myyd@outlook.com>
 * @date 2017-3-14
 * @param int $page
 * @param int $limit
 * @return array
 */
function build_limit($page = 1, $limit = 20)
{
    /**page 默认值添加判断，必须大于0*/
    $page = $page >= 1 ? $page : 1;
    $return = array(
        'begin' => ($page - 1) * $limit,
        'offset' => $limit
    );
    return $return;
}

/**
 * 通过经纬度计算两点之间的距离
 *
 * @author mayy
 * @date 2017-4-13
 * @param $lat1 点1纬度
 * @param $lng1 点1经度
 * @param $lat2 点2纬度
 * @param $lng2 点2经度
 * @return boolean | float  返回距离（米）
 */
if (!function_exists('getDistance')) {
    function get_dsistance($lat1, $lng1, $lat2, $lng2){
        if (empty($lat1) || empty($lng1) || empty($lat2) || empty($lng2)) {
            return false;
        }
        $earthRadius = 6367000; //approximate radius of earth in meters
        $lat1 = ($lat1 * pi() ) / 180;
        $lng1 = ($lng1 * pi() ) / 180;
        $lat2 = ($lat2 * pi() ) / 180;
        $lng2 = ($lng2 * pi() ) / 180;
        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;
        return round($calculatedDistance);
    }
}

/**
 * 生成随机字符串
 *
 * @author mayy
 * @date 2017-4-13
 * @param int $len 要生成的字符串长度
 * @param string $type 要生成的字符串类型 0：大写小写字母组合 1：数字  2：大写字母 3：小写字母 4：汉字 默认：字母、大写、小写字母的组合
 * @param string $addChars 追加的字符串
 * @return string
 */
function rand_string($len = 6, $type = '', $addChars = '')
{
    $str = '';
    switch ($type) {
        case 0:
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars ;
            break;
        case 1:
            $chars = str_repeat('0123456789', 3);
            break;
        case 2:
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
            break;
        case 3:
            $chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
            break;
        case 4:
            $chars = "们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借" . $addChars;
            break;
        default :
            // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
            $chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
            break;
    }
    if ($len > 10) {//位数过长重复字符串一定次数
        $chars = $type == 1 ? str_repeat($chars, $len) : str_repeat($chars, 5);
    }
    if ($type != 4) {
        $chars = str_shuffle($chars);
        $str = substr($chars, 0, $len);
    } else {
        // 中文随机字
        for ($i = 0; $i < $len; $i++) {
            $str .= msubstr($chars, floor(mt_rand(0, mb_strlen($chars, 'utf-8') - 1)), 1);
        }
    }
    return $str;
}

/**
 * 数据采集
 *
 * @param string $durl 目标url
 * @param integer $timeout 超时秒数
 * @return mixed
 */
function curl_file_get_contents($durl, $timeout = 10){
    if (empty($durl) || (int)$timeout <= 0) {
        return false;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $durl);
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $r = curl_exec($ch);
    curl_close($ch);
    return $r;
}

/**
 * 远程判断文件是否存在
 * @param string $url 目标url
 * @return boolean
 */
function file_check_exists($url = '')
{
    if (!$url) {
        return false;
    }

    $array = get_headers($url,1);
    if(preg_match('/200/',$array[0])){
        return true;
    }else{
        return false;
    }
}

/**
 * 验证手机号
 * @param string $phone 手机号
 * @return bool
 */
function is_mobile_phone($phone)
{
    if (empty($phone) || !is_numeric($phone) || strlen($phone) != 11) {
        return false;
    }
    return preg_match("/^000[0-9]{8}$|13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|14[0-9]{1}[0-9]{8}$|17[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$/", $phone);
}

/**
 * 电话号码或是手机号码 隐藏中间数字，以星号代替  例：133****1111
 *
 * @param unknown $phone
 * @return mixed
 */
function hidden_phone($phone)
{
    $IsWhat = preg_match('/(0[0-9]{2,3}[-]?[2-9][0-9]{6,7}[-]?[0-9]?)/i', $phone); //固定电话
    if ($IsWhat == 1) {
        return preg_replace('/(0[0-9]{2,3}[-]?[2-9])[0-9]{3,4}([0-9]{3}[-]?[0-9]?)/i', '$1****$2', $phone);
    } else {
        return preg_replace('/(1[358]{1}[0-9])[0-9]{4}([0-9]{4})/i', '$1****$2', $phone);
    }
}

/**
 * 验证输入的邮件地址是否合法
 *
 * @param  string $email 需要验证的邮件地址
 * @return bool
 */
function is_email($email)
{
    $chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
    if (strpos($email, '@') !== false && strpos($email, '.') !== false) {
        if (preg_match($chars, $email)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function  is_chinese($str)
{
    //[\u4E00-\u9FFF]+
    $pattern = '#[\x{4e00}-\x{9fa5}]+#u';
    return preg_match($pattern, $str);
}

/**
 * 对二维数组进行按照指定字段进行排序（升序或者降序）
 *
 * @param $arr
 * @param string $field 指定字段
 * @param int $sort 0:默认升序， 1：降序
 * @return boolean | void
 */
function sort_array(&$arr, $field, $sort = 0)
{
    if (!is_array($arr)) {
        return false;
    } else {
        if (count($arr) <= 1) {
            return $arr;
        } else {
            if (!is_array($arr[0]) || !isset($arr[0][$field])) {
                return false;
            }
        }
    }

    $temp = [];
    foreach ($arr as $key => $value) {
        $temp[$key]=$value[$field];
    }

    //SORT_ASC SORT_DESC
    $sort_by = $sort == 0 ? SORT_ASC : SORT_DESC;
    array_multisort($temp,$sort_by,$arr);
}

/**
 * XML编码
 * @param mixed $data 数据
 * @param string $root 根节点名
 * @param string $item 数字索引的子节点名
 * @param string $attr 根节点属性
 * @param string $id   数字索引子节点key转换的属性名
 * @param string $encoding 数据编码
 * @return string
 */

if (!function_exists('xml_encode')) {
    function xml_encode($data, $root='think', $item='item', $attr='', $id='id', $encoding='utf-8') {
        if(is_array($attr)){
            $_attr = array();
            foreach ($attr as $key => $value) {
                $_attr[] = "{$key}=\"{$value}\"";
            }
            $attr = implode(' ', $_attr);
        }
        $attr   = trim($attr);
        $attr   = empty($attr) ? '' : " {$attr}";
        $xml    = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
        $xml   .= "<{$root}{$attr}>";
        $xml   .= data_to_xml($data, $item, $id);
        $xml   .= "</{$root}>";
        return $xml;
    }
}

if (!function_exists('data_to_xml')) {
    /**
     * 数据XML编码
     * @param mixed  $data 数据
     * @param string $item 数字索引时的节点名称
     * @param string $id   数字索引key转换为的属性名
     * @return string
     */
    function data_to_xml($data, $item='item', $id='id') {
        $xml = $attr = '';
        foreach ($data as $key => $val) {
            if(is_numeric($key)){
                $id && $attr = " {$id}=\"{$key}\"";
                $key  = $item;
            }
            $xml    .=  "<{$key}{$attr}>";
            $xml    .=  (is_array($val) || is_object($val)) ? data_to_xml($val, $item, $id) : $val;
            $xml    .=  "</{$key}>";
        }
        return $xml;
    }
}

/**
* 调试函数，仅用于调试使用
* @author mayy<myyd@outlook.com>
* @date 2017-3-7
* @param array $data
* @return void
*/
function dd($data)
{
    var_dump($data);
    exit;
}

/**
 * 调试函数，仅用于调试使用
 * @author 曾有
 * @date 2017-11-16
 * @param array $data
 * @return void
 */
function dd2($data)
{
    echo '<pre />';
    print_r($data);
    exit;
}

/**
 * 获取客户端IP地址
 * @param integer   $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean   $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function get_client_ip($type = 0, $adv = false)
{
    $type      = $type ? 1 : 0;

    if ($adv) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos) {
                unset($arr[$pos]);
            }
            $ip = trim(current($arr));
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip   = $long ? [$ip, $long] : ['0.0.0.0', 0];
    return $ip[$type];
}

/**
 * 返回是否是通过浏览器访问的页面
 *
 * @author wj
 * @param  void
 * @return boolen
 */
function is_browser()
{
    static $ret_val = null;
    if ($ret_val === null) {
        $ret_val = false;
        $ua = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';
        if ($ua) {
            if ((strpos($ua, 'mozilla') !== false) && ((strpos($ua, 'msie') !== false) || (strpos($ua, 'gecko') !== false))) {
                $ret_val = true;
            } elseif (strpos($ua, 'opera')) {
                $ret_val = true;
            }
        }
    }
    return $ret_val;
}

/**
 * 递归把数组中全有为null的元素值换成空字符串
 *
 * @author mayy
 * @date 2017-8-15
 * @param $arr
 * @return mixed
 */
function null_to_empty_string($arr)
{
    array_walk_recursive($arr, function (&$val) {
        if ($val === null) {
            $val = '';
        }
    });

    return $arr;
}

/**
 * 转换数据库中的图片路径为http可访问地址
 *
 * @author mayy
 * @date 2017-8-15
 * @param string $url
 * @param boolean $need_domain 是否需要域名前缀
 * @return mixed
 */
function get_img_http_url($url, $need_domain = true)
{
    $path = str_replace(WWW_PATH, '', IMG_PATH);
    return $need_domain ? get_domain() . '/' . $path . $url : $path . $url;
}


/**
 * 检查是否是一个常规的编号数据，仅包含：字母、数字、下划线、横线
 * @param $number
 * @return bool
 */
function is_normal_number($number)
{
    if (empty($number)) {
        return false;
    }

    $chars = "/^[a-zA-Z0-9_-]*$/i";

    if (preg_match($chars, $number)) {
        return true;
    }

    return false;
}

/**
 * 检查是否是经纬度数据,仅验证 123.123456, 23.12345格式的
 * @param $number
 * @return boolean
 */
function is_longitude_or_latitude($number)
{
    if (empty($number)) {
        return false;
    }

    $chars = "/^\d{2,3}\.\d{2,}$/i";

    if (preg_match($chars, $number)) {
        return true;
    }

    return false;
}

/**
 * 检查字符串长度是否超出指定长度
 *
 * @author mayy
 * @date 2017-9-6
 * @param string $str 被检查的字符串
 * @param integer $len 限制的长度
 * @param string $charset 编码类型
 * @return boolean 超出：true，未超出：false
 */
function check_str_is_out_range($str, $len = 255, $charset = 'utf-8')
{
    return iconv_strlen($str,$charset) > $len;
}

/**
 * 检查参数是否是时间格式数据
 *
 * @param $dateTime
 * @return bool
 */
function isDateTime($dateTime){
    $ret = strtotime($dateTime);
    return $ret !== FALSE && $ret != -1;
}

/**
 * 字典数组关系转换
 *
 * @param array $arr
 * @return array
 */
function arr_raltion_change(array $arr)
{
    if (empty($arr)) {
        return array();
    }

    $return = $tmp = array();
    foreach ($arr as $k => $v) {
        $tmp['key']     = $k;
        $tmp['value']   = $v;
        $return[]       = $tmp;
    }

    return $return;
}

/**
 * 对二维数组去重处理
 *
 * @author mayy
 * @date 2017-9-25
 * @param array $arr
 * @return array
 */
function double_dimensional_array_unique(array $arr)
{
    if (empty($arr)) {
        return [];
    }

    $return = [];
    foreach ($arr as $v) {
        $return[] = json_encode($v);
    }

    $return = array_unique($return);
    foreach ($return as $k=>$v) {
        $return[$k] = json_decode($v, true);
    }

    return $return;
 }

/**
 * 检查数字是否是合法的经纬度数据
 *
 * @author mayy
 * @date 2017-10-16
 * @param $number
 * @return boolean
 */
 function check_is_location_number($number)
 {
     if (empty($number)) {
         return false;
     }

     $peg = "/^\d{1,3}\.\d{1,10}$/";
     if (preg_match($peg, $number)) {
        return true;
     }

     return false;
 }

/**
 * 获得锁对象
 *
 * @author mayy
 * @date 2017-10-20
 * @param string $key 锁的key
 * @param integer $timeout 锁超时时间
 * @param string $type 锁类型
 * @param array $config 配置参数
 * @return mixed
 */
 function get_lock($key, $timeout = 3, $type = '', array $config = [])
 {
     if (empty($key)) {
         return false;
     }

     return app\common\com\disLock\DisLockFactory::getInstance($type, $config)->lock($key, $timeout);
 }

/**
 * 解锁操作
 *
 * @param $key
 * @param string $type
 * @param array $config
 * @return bool
 */
 function unlock($key, $type = '', array $config = [])
 {
     return app\common\com\disLock\DisLockFactory::getInstance($type, $config)->unlock($key);
 }


/**
 * 处理按照顺序排列的数字型数组，按照连续性进行拆分
 * 示例：[1,2,3,4,7,8,21] 会被拆分成：[[1,4],[7,8],21]
 *
 * @author mayy
 * @date 2018-04-28
 * @param $order_int_arr
 * @param array $res 需要存放结果的数组
 * @return null
 */
function subOrderIntArr($order_int_arr, array &$res)
{
    $len = count($order_int_arr);

    $start = $order_int_arr[0];

    if ($len <= 2) {
        $res[] = $order_int_arr;
        return;
    }

    for ($i=0;$i<$len;$i++) {
        if ($order_int_arr[$i] + 1 == $order_int_arr[$i + 1]) {
            if ($i == $len - 2) {
                $res[] = [$start, $order_int_arr[$i + 1]];
                return;
            }
            continue;
        } else {
            $res[] = [$start, $order_int_arr[$i]];
            subOrderIntArr(array_slice($order_int_arr, $i+1), $res);
            break;
        }
    }

    return;
}