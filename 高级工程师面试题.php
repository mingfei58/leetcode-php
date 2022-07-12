<?php
/**
 * Definition for a singly-linked list.
 *
 */
class ListNode {
    public $val = 0;
    public $next = null;
    function __construct($val) { $this->val = $val; }
}

/**
 * 1.给你四个坐标点，判断它们能不能组成一个矩形，如判断 ([0,0],[0,1],[1,1],[1,0]) 能组成一个矩形。
 * 考察应用数学知识进行逻辑推理
 */

function rectangle($p1,$p2,$p3,$p4)
{
    //有三个角是直角的四边形是矩形
    //证明$p1,$p2,$p3满足勾股定理
    if(!rightAngle($p1,$p2,$p3)){
        return false;
    }
    //证明$p1,$p2,$p4满足勾股定理
    if(!rightAngle($p1,$p2,$p4)){
        return false;
    }
    //证明$p2,$p3,$p4满足勾股定理
    if(!rightAngle($p2,$p3,$p4)){
        return false;
    }
    return true;
}
function rightAngle($p1,$p2,$p3)
{
    $l1 = pow(abs($p1[1] - $p2[1]),2) + pow(abs($p1[0]-$p2[0]),2);
    $l2 = pow(abs($p1[1] - $p3[1]),2) + pow(abs($p1[0]-$p3[0]),2);
    $l3 = pow(abs($p2[1] - $p3[1]),2) + pow(abs($p2[0]-$p3[0]),2);
    if($l1+$l2 == $l3 || $l1+$l3 == $l2 || $l2+$l3 == $l1){
        return true;
    }
    return false;
}
//var_dump(rectangle([1,0],[0,1],[2,1],[1,2]));


/**
 * 2.写一段代码判断单向链表中有没有形成环，如果形成环，请找出环的入口处，即 P 点
 * 考察链表双指针定位
 */
$n1 = new ListNode(1);
$n2 = new ListNode(2);
$n3 = new ListNode(3);
$n4 = new ListNode(4);
$n5 = new ListNode(5);

$n1->next = $n2;
$n2->next = $n3;
$n3->next = $n4;
$n4->next = $n5;
$n5->next = $n3;
function loopStartPoint($list)
{
    $l1 = $list->next;
    $l2 = $list->next->next;
    while ($l2->val != null){
        if($l1->val == $l2->val){
            break;
        }
        $l1 = $l1->next;
        $l2 = $l2->next->next;
    }
    if($l2->val == null){
        return false;
    }
    //找到相交点
    $l1 = $list->next;
    $l2 = $l2->next;
    while(true){
        if($l1->val == $l2->val){
            break;
        }
        $l1 = $l1->next;
        $l2 = $l2->next;
    }
    return $l1;
}
//var_dump(loopStartPoint($n1));

/**
 * 3.写一个函数，获取一篇文章内容中的全部图片，并下载
 *  考察正则表达式，curl请求，文件使用
 */
function downloadImage($url,$path="tmp")
{
    $content = file_get_contents($url);

    $reg = "/<img.*?\"(.*(jpg|bmp|jpeg|gif|png)).*?>/";
    preg_match_all($reg,$content,$matches);
    $imgArr = $matches[1];
    print_r($imgArr);

    $dir = getcwd().DIRECTORY_SEPARATOR.$path;
    mkdir($dir,0777,true);
    foreach ($imgArr as $img){
        $ch = curl_init($img);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_NOBODY,0);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_PROXY_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $fileInfo = curl_exec($ch);
        curl_close($ch);
        if(!$fileInfo){
            continue;
        }
        $ext = strrchr($img,".");
        $filename = $dir.DIRECTORY_SEPARATOR.uniqid().$ext;
        $handle = fopen($filename,"w");
        fwrite($handle,$fileInfo);
        fclose($handle);
    }
}
//downloadImage("https://music.163.com/");

/**
 * 4.获取当前客户端的 IP 地址，并判断是否在（111.111.111.111,222.222.222.222)
 */
function getClientIp(){
    if($_SERVER["HTTP_X_FORWARDED_FOR"] != null){
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    return $_SERVER["REMOTE_ADDR"];
}
//var_dump(getClientIp());

/**
 * 5.nginx 的 log_format 配置如下:
 * log_format main ‘remoteaddr−remote_user [timelocal]"request”’ ‘statusbody_bytes_sent “httpreferer"″"http_user_agent” “upstreamresponsetime""request_time” “http_x_forwarded_for"';
 * 从今天的 nginx log 文件 access.log 中：
 *    a、列出 “request_time” 最大的 20 行？
 *    b、列出早上 10 点访问量做多的 20 个 url 地址？
 * 考察awk 基本语法 分组统计
 * 考察grep 用法
 * 考察sort 用法
 * 考察head 用法
 */

// a. cat log | awk "print $9" | sort -n -r | head -n 20
// b. cat log | grep "19/Apr/2022:11:[01-59]" | awk '{x[$4]++;} END{for(i in x) print(i,x[i])}' | sort -r -n -k 2 | head -n 20

/**
 * 6.什么是 CSRF 攻击？XSS 攻击？如何防范?
 */

/**
 * 7.应用中我们经常会遇到在 user 表随机调取 10 条数据来展示的情况，简述你如何实现该功能
 */
// select * from user where id>=(select floor(max(id) * rand()) from user) limit 10;


/**
 * 8.从扑克牌中随机抽 5 张牌，判断是不是一个顺子，即这 5 张牌是连续的
 */

/**
 * 9.两条相交的单向链表，如何求它们的第一个公共节点
 */

function firstComNode($l1,$l2)
{
    $s1 = $l1->next;
    $s2 = $l2->next;
    $finish1 = false;
    $finish2 = false;
    $firstNode = null;
    while(true){
        if($finish1 && $finish2 && $s1 === $s2){
            $firstNode = $s1;
            break;
        }
        if($s1!=null){
            $s1 = $s1->next;
        }elseif(!$finish1){
            $finish1 = true;
            $s1 = $l2->next;
        }else{
            break;
        }
        if($s2!=null){
            $s2 = $s2->next;
        }elseif(!$finish2){
            $finish2 = true;
            $s2 = $l1->next;
        }else{
            break;
        }
    }
    return $firstNode;
}

$n1 = new ListNode(1);
$n2 = new ListNode(5);
$n3 = new ListNode(3);
$n4 = new ListNode(3);
$n5 = new ListNode(5);
$n6 = new ListNode(5);
$n7 = new ListNode(7);
$n8 = new ListNode(8);

$n1->next = $n2;
$n2->next = $n6;
$n6->next = $n7;
$n7->next = $n8;

$n3->next = $n4;
$n4->next = $n5;
$n5->next = $n6;
var_dump(firstComNode($n1,$n3));


/**
 * 10.最长公共子序列问题 LCS，如有 [1,2,5,11,32,15,77] 和 [99,32,15,5,1,77] 两个数组，找到它们共同都拥有的数，写出时间复杂度最优的代码，不能用 array_intersect
 */

function longComStr($arr1,$arr2)
{
    $arr = [];
    $return = [];
    for($i=0,$len=count($arr1);$i<$len;$i++){
        $arr[$arr1[$i]] = 1;
    }
    for($j=0,$len2=count($arr2);$j<$len2;$j++){
        if(isset($arr[$arr2[$j]])){
            $return[] = $arr2[$j];
        }
    }
    return $return;
}
//print_r(longComStr([99,32,15,5,1,77],[1,2,5,11,32,15,77]));

/**
 * 11.MYSQL 中主键与唯一索引的区别
 */
// 1. 查询速度上 主键索引更快
// 2. 设置值时 主键索引不能为空 唯一索引可以
// 3. 索引结构上 主键索引叶子结点保存的是完整数据 唯一索引叶子结点保存的是主键索引的数据

/**
 * 12.http 与 https 的主要区别
 */
// 1. 端口: 80 vs 443
// 2. 加密传输：否 vs 是
// 3. 安全性： 低 vs 高
// 4. 认证证书： 不需要 vs 需要
// 5. HTTPS基于ssl连接创建http请求

// -> 客户端发起HTTPS请求
// -> 服务端必须有数字证书
// -> 客户端验证公钥的有效性（颁发机构及过期时间）,产生随机值，通过后将传输数据进行非对称加密
// -> 服务端通过私钥解密数据，并根据随机值对数据进行对称加密发送给客户端
// -> 客户端通过随机值解密数据

/**
 * 13 http 状态码及其含意
 */


/**
 * 30.写个函数，判断下面扩号是否闭合，左右对称即为闭合： ((()))，)(())，(())))，(((((())，(()())，()()
 */
function symmetry($str)
{
    $strlen = strlen($str);
    $left = 0;
    $right = $strlen-1;
    while (true){
        if($str[$left] != mirrorBracket($str[$right])){
            return false;
        }
        if($left>=$right){
            break;
        }
        $left++;
        $right--;
    }
    return true;
}
function mirrorBracket($char)
{
    if($char == "("){
        return ")";
    }
    if($char == ")"){
        return "(";
    }
    return $char;
}
//var_dump(symmetry("((()))"),symmetry(")(())"),symmetry("(())))"),symmetry("(((((())"),symmetry("(()())"),symmetry("()()"));

/**
 * 31.找出数组中不重复的值 [1,2,3,3,2,1,5]
 */
function filterRepeatVal($arr)
{
    $return = [];
    for($i=0,$len = count($arr);$i<$len;$i++){
        if(isset($return[$arr[$i]])){
            $return[$arr[$i]] ++;
        }else{
            $return[$arr[$i]] = 1;
        }
    }
    foreach($return as $k=>$val){
        if($val>1){
            unset($return[$k]);
        }
    }
    return array_keys($return);
}
//var_dump(filterRepeatVal([1,2,3,3,2,1,5]));
/**
 * 32.PHP 的的这种弱类型变量是怎么实现的？
 */

/**
 * 33.在 HTTP 通讯过程中，是客户端还是服务端主动断开连接？
 */

/**
 * 34.PHP 中发起 http 请求有哪几种方式？它们有何区别？
 */

/**
 * 35.有一颗二叉树，写代码找出来从根节点到 flag 节点的最短路径并打印出来，flag 节点有多个。比如下图这个树中的 6 和 14 是 flag 节点，请写代码打印 8、3、6 和 8、10、14 两个路径
 */

/**
 * 36.有两个文件，大小都超过了 1G，一行一条数据，每行数据不超过 500 字节，两文件中有一部分内容是完全相同的，请写代码找到相同的行，并写到新文件中。PHP 最大允许内内为 255M。
 */
//相当于把两个大数组拆分成若干个小数组，循环遍历小数组，找出相同元素，组成新数组

/**
 * 37.请写出自少两个支持回调处理的 PHP 函数，并自己实现一个支持回调的 PHP 函数
 */
function u_array_filter($arr ,Callable $callback)
{
    for($i=0,$len=count($arr);$i<$len;$i++){
        if($callback($arr[$i]) === false){
            unset($arr[$i]);
        }
    }
    return array_values($arr);
}
//var_dump(u_array_filter([1,3,5],function($item){
//    return $item>3;
//}));
/**
 * 38.请写出自少两个获取指定文件夹下所有文件的方法
 */

/**
 * 39.请写出自少三种截取文件名后缀的方法或函数
 */

/**
 * 40.PHP 如何实现不用自带的 cookie 函数为客户端下发 cookie。对于分布式系统，如何来保存 session 值。
 */
/**
 * decrypt AES 256
 *
 * @param string $edata
 * @param string $password
 * @return string data
 */
function decrypt($edata, $password) {
    $data = base64_decode($edata);
    $salt = substr($data, 0, 16);
    $ct = substr($data, 16);

    $rounds = 3; // depends on key length
    $data00 = $password.$salt;
    $hash = array();
    $hash[0] = hash('sha256', $data00, true);
    $result = $hash[0];
    for ($i = 1; $i < $rounds; $i++) {
        $hash[$i] = hash('sha256', $hash[$i - 1].$data00, true);
        $result .= $hash[$i];
    }
    $key = substr($result, 0, 32);
    $iv  = substr($result, 32,16);

    return openssl_decrypt($ct, 'AES-256-CBC', $key, true, $iv);
}

/**
 * crypt AES 256
 *
 * @param string $data
 * @param string $password
 * @return string encrypted data
 */
function encrypt($data, $password) {
    // Set a random salt
    $salt = openssl_random_pseudo_bytes(16);

    $salted = '';
    $dx = '';
    // Salt the key(32) and iv(16) = 48
    while (strlen($salted) < 48) {
        $dx = hash('sha256', $dx.$password.$salt, true);
        $salted .= $dx;
    }

    $key = substr($salted, 0, 32);
    $iv  = substr($salted, 32,16);

    $encrypted_data = openssl_encrypt($data, 'AES-256-CBC', $key, true, $iv);
    return base64_encode($salt . $encrypted_data);
}
class EncryptedSessionHandler extends SessionHandler
{
    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function read($id)
    {
        $data = parent::read($id);
        echo "read from ".$id."\n";
        if (!$data) {
            return "";
        } else {
            return decrypt($data, $this->key);
        }
    }

    public function write($id, $data)
    {
        $data = encrypt($data, $this->key);
        echo "write from ".$id."\n";
        return parent::write($id, $data);
    }
}
//ini_set('session.save_handler', 'files');
//
//$key = 'secret_string';
//$handler = new EncryptedSessionHandler($key);
//session_set_save_handler($handler, true);
//session_start();
////$_SESSION["delete_time"] = time();
//var_dump($_SESSION);

//字符串（int embstr raw） 哈希(ziplist hashtable) 列表(ziplist linkedlist) 集合(intset hashtable) 有序集合(ziplist skiplist)
/**
 * 41.请用 SHELL 统计 5 分钟内，nginx 日志里访问最多的 URL 地址，对应的 IP 是哪些？
 */

/**
 * 42.mysql 数据库中 innodb 和 myisam 引擎的区别
 */

/**
 * 43.从用户在浏览器中输入网址并回车，到看到完整的见面，中间都经历了哪些过程。
 */

/**
 * 44.如何分析一条 sql 语句的性能。
 */

/**
 * 45.$a=[0,1,2,3]; $b=[1,2,3,4,5]; $a+=$b; var_dump ($a) 等于多少
 */
$a=[0,1,2,3]; $b=[1,2,3,4,5];
//$a+=$b;
$a = array_merge($a,$b);
//var_dump ($a);

/**
 * 46.$a=[1,2,3]; foreach ($a as &$v){} foreach ($a as $v){} var_dump ($a)
 */
$v = 3;
$a=[1,2,&$v];

foreach ($a as $v){}
var_dump ($a);
/**
 * 47.数据库中的存放了用户 ID, 扣费很多行，redis 中存放的是用户的钱包，现在要写一个脚本，将数据库中的扣费记录同步到 redis 中，每 5 分钟执行一次。请问要考虑哪些问题？
 */

function syncData()
{
    //防重复
    //避开高峰期
    //定时任务
    //连接池
    //协程
}
/**
 * 48.MYSQL 主从服务器，如果主服务器是 innodb 引擎，从服务器是 myisam 引擎，在实际应用中，会遇到什么问题？
 */

/**
 * 49.linux 中进程信号有哪些？
 */

/**
 * 50.异步模型
 */

/**
 * 51.10g 文件，用 php 查看它的行数
 */

/**
 * 52.有 10 亿条订单数据，属于 1000 个司机的，请取出订单量前 20 的司机
 */

/**
 * 53.根据 access.log 文件统计最近 5 秒的 qps，并以如下格式显示，01 1000
 */

/**
 * 54.有一个 1G 大小的一个文件，里面每一行是一个词，词的大小不超过 16 个字节，内存限制大小是 1M。返回频数最高的 100 个词
 */

/**
 * 55.php 进程模型，php 怎么支持多个并发
 */

/**
 * 56.nginx 的进程模型，怎么支持多个并发
 */

/**
 * 57.让你实现一个简单的架构，并保持高可用，两个接口，一个上传一条文本，一个获取上传的内容，你怎么来设计？要避免单机房故障，同时要让代码层面无感。
 */

/**
 * 58.两台 mysql 服务器，其中一台挂了，怎么让业务端无感切换，并保证正常情况下讲台服务器的数据是一致的
 */

/**
 * 59.http 协议具体的定义
 */

/**
 * 60.什么是锁，怎么解决锁的问题
 */

/**
 * 61.mysql 事务隔离是怎么实现的
 */

/**
 * 62.mysql 的锁怎么实现的
 */

/**
 * 69.对称加密和非对称加密的方式
 */

/**
 * 70.10 瓶水，其中一瓶有毒，小白鼠喝完有毒的水之后，会在 24 小时后死亡，问：最少用几只小白鼠可以在 24 小时后找到具体是哪一瓶水有毒。
 */

/**
 * 71.redis 是如何进行同步的，同步的方式，同步回滚怎么办，数据异常怎么办，同时会问 MYSQL 的同步方式和相关异常情况
 */

/**
 * 72.Trait 优先级
 */

/**
 * 73.在一个坐标系内有一个 N 个点组成的多边形，现在有一个坐标点，写代码或思路来判断这个点是否处于多边形内
 */

/**
 * 74.数据库如果出现了死锁，你怎么排查，怎么判断出现了死锁？
 */

/**
 * 75.写一个一个程序来查找最长子串
 */

/**
 * 76.分析一个问题:php-fpm 的日志正常，但客户端却超时了，你认为可能是哪里出了问题，怎么排查？
 */