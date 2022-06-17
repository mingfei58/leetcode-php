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
    while(true){
        if($finish1 && $finish2 && $s1->val == $s2->val){
            break;
        }
        if($s1!=null){
            $s1 = $s1->next;
        }else{
            $finish1 = true;
            $s1 = $l2->next;
        }
        if($s2!=null){
            $s2 = $s2->next;
        }else{
            $finish2 = true;
            $s2 = $l1->next;
        }
    }
    return $s1->val;
}

$n1 = new ListNode(1);
$n2 = new ListNode(2);
$n3 = new ListNode(3);
$n4 = new ListNode(4);
$n5 = new ListNode(5);
$n6 = new ListNode(6);
$n7 = new ListNode(7);
$n8 = new ListNode(8);

$n1->next = $n2;
$n2->next = $n6;
$n6->next = $n7;
$n7->next = $n8;

$n3->next = $n4;
$n4->next = $n5;
$n5->next = $n6;
//print_r(firstComNode($n1,$n3));



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