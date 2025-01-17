<?php
//https://learnku.com/articles/20714
//http://c.biancheng.net/view/3339.html
//https://www.modb.pro/db/72128
//https://www.edrawmax.cn/online/zh/workbench
//https://www.cnblogs.com/dongry/p/10210609.html

//require_once "高级工程师面试题.php";
//require_once "协程/UseYield.php";
//数据结构

//存储具有复杂关系的数据方便于后期对数据的再利用
class Solution {

    /**
     * 冒泡排序
     * 时间复杂度 O(n^2)
     * 空间复杂度 O(1)
     * @param Integer[] $nums
     * @return Integer[]
     */
    function bubbleSort($nums) {
        $len = count($nums);
        for($i=0;$i<$len;$i++){
            for($j=0;$j<$len-$i-1;$j++){
                if($nums[$j]>$nums[$j+1]){
                    $temp = $nums[$j];
                    $nums[$j] = $nums[$j+1];
                    $nums[$j+1] = $temp;
                }
            }
        }
        return $nums;
    }

    /**
     * 选择排序
     * 时间复杂度 O(n^2)
     * 空间复杂度 O(1)
     * @param Integer[] $nums
     * @return Integer[]
     */
    function selectionSort($nums){
        $len = count($nums);
        for($i=0;$i<$len;$i++){
            $min = $i;
            for($j=$i+1;$j<$len;$j++){
                if($nums[$min] > $nums[$j]){
                    $min = $j;
                }
            }
            if($min != $i){
                list($nums[$i],$nums[$min]) = array($nums[$min],$nums[$i]);
            }
        }
        return $nums;
    }
    /**
     * 快速排序
     * 时间复杂度 O(n^2)
     * 空间复杂度 O(logn)
     * @param Integer[] $nums
     * @return Integer[]
     */
    function quickSort($nums){
        $len=count($nums);
        if($len <= 1){
            return $nums;
        }
        $base = $nums[0];
        $left = $right = [];
        for ($i=1;$i<$len;$i++){
            if($nums[$i]<$base){
                $left[] = $nums[$i];
            }else{
                $right[] = $nums[$i];
            }
        }
        $left = $this->quickSort($left);
        $right = $this->quickSort($right);
        return array_merge($left,[$base],$right);
    }
    /**
     * 插入排序
     * 时间复杂度 O(n^2)
     * 空间复杂度 O(1)
     * @param Integer[] $nums
     * @return Integer[]
     */
    function insertionSort(array $nums){
        $len = count($nums);
        for ($i=1;$i<$len;$i++){
            for ($j = $i;$j>0;$j--){
                if($nums[$j] < $nums[$j-1]){
                    $temp = $nums[$j];
                    $nums[$j] = $nums[$j-1];
                    $nums[$j-1] = $temp;
                }
            }
        }
        return $nums;
    }
    //归并切分块大小
    const CHUNK_SIZE = 2;
    /**
     * 归并排序
     * 时间复杂度 O(nlogn)
     * 空间复杂度 O(n)
     * @param Integer[] $nums
     * @return Integer[]
     */
    function mergeSort(array $nums){
        //分块
        $chunks = array_chunk($nums,self::CHUNK_SIZE);
        while(count($chunks) > 1){
            $objectSort1 = $this->quickSort(array_pop($chunks));
            $objectSort2 = $this->quickSort(array_pop($chunks));
            $l = count($objectSort1);
            //两两合并
            for($i=0,$l1 = count($objectSort2);$i<$l1;$i++){
                $objectSort1[$l] = $objectSort2[$i];
                $l++;
                for($j=$l-1;$j>0;$j--){
                    if($objectSort1[$j] < $objectSort1[$j-1]){
                        $temp = $objectSort1[$j];


                        $objectSort1[$j] = $objectSort1[$j-1];
                        $objectSort1[$j-1] = $temp;
                    }
                }
            }
            array_unshift($chunks,$objectSort1);
        }

        return array_pop($chunks);
    }

    /**
     * 堆排序
     * 时间复杂度 O(nlogn)
     * 空间复杂度 O(1)
     * @param Integer[] $arr
     * @return Integer[]
     */
    function heapSort(&$arr){
        $len = count($arr);
        for ($i = ($len>>1)-1;$i>=0;$i--){
            $this->heapAdjust($arr,$i,$len);
        }
        for($i = $len -1;$i>=0;$i--){
            list($arr[0],$arr[$i]) = array($arr[$i],$arr[0]);
            $this->heapAdjust($arr,0,$i);
        }
        return $arr;
    }

    /**
     * 调整堆
     * @param $arr
     * @param $start
     * @param $len
     */
    function heapAdjust(&$arr,$start,$len){
        for($child = $start*2+1;$child<$len;$child = 2*$child+1){
            if($child != $len-1 && $arr[$child]<$arr[$child+1]){
                $child++;
            }
            if($arr[$start] > $arr[$child]){
                break;
            }
            list($arr[$start],$arr[$child]) = array($arr[$child],$arr[$start]);
            $start = $child;
        }
    }
    const DIGIT = 10;
    /**
     * 基数排序
     * 时间复杂度 O(n*k)
     * 空间复杂度 O(n+k)
     * @param Integer[] $arr
     * @return Integer[]
     */
    public function radixSort($arr,$radix=1){
        $deep = 1;
        while($radix>0){
            $bucket = array_pad([],self::DIGIT,[]);//桶
            foreach($arr as $v){
                $k = ($v/$deep)%10;
                array_push($bucket[$k],$v);
            }
            $k = 0;
            for($i=0;$i<self::DIGIT;$i++){
                if(empty($bucket[$i])) continue;
                while($n = array_shift($bucket[$i])){
                    $arr[$k] = $n;
                    $k++;
                }
            }
            $radix--;
            $deep *=10;
        }
        return $arr;
    }

    /**
     * 计数排序
     * 时间复杂度 O(n+k)
     * 空间复杂度 O(k)
     * @param Integer[] $arr
     * @param $max
     * @param int $min
     * @param int $step
     * @return Integer[]
     */
    public function coutingSort($arr,$max,$min=0,$step=1){
        $len = count($arr);
        $bucket = [];
        for($i=0;$i<$len;$i++){
            $bucket[$arr[$i]] = ($bucket[$arr[$i]]??0)+1;
        }
        $j = 0;
        for($i = $min;$i <= $max;$i += $step){
            if(empty($bucket[$i])){
                continue;
            }
            while($bucket[$i]>0){
                $arr[$j] = $i;
                $j++;
                $bucket[$i] --;
            }
        }
        return $arr;

    }
    /**
     * @param Integer[][] $grid
     * @return Integer
     */
    function maxValue($grid) {
        //dp是二维数组，dp[i][j]表示在当前坐标下礼物的最大价值
        $dp = [];
        $l1 = count($grid);
        $l2 = count($grid[0]);
        for($i=0;$i<$l1;$i++){
            for ($j=0;$j<$l2;$j++){
                if($i == 0 && $j == 0){
                    $dp[$i][$j] = $grid[$i][$j];
                }elseif($i == 0 || ($dp[$i][$j-1]>$dp[$i-1][$j])){
                    $dp[$i][$j] = $grid[$i][$j] + $dp[$i][$j-1];
                }else{
                    $dp[$i][$j] = $grid[$i][$j] + $dp[$i-1][$j];
                }
            }
        }
        return $dp[$l1-1][$l2-1];
    }
    function translateNum($num) {
        $num = strval($num);
        $len = strlen($num);
        if($len==0){
            return 1;
        }
        //dp数组key表示数字长度value表示当前长度有多少种翻译情况翻译
        $dp[0] = 1;
        $dp[1] = 1;
        for ($i=1;$i<$len;$i++){
            $k = $i +1;
            if($num[$i-1] == 1 || ($num[$i-1] == 2 && $num[$i]<=5)){
                $dp[$k] = $dp[$k-2] + $dp[$k-1];
            }else{
                $dp[$k] = $dp[$k-1];
            }
        }
        return $dp[$len];
    }
    function maxSubArray($nums) {
        $len=count($nums);
        if($len==0){
            return 0;
        }
        //dp数组k对应的数值表示nums[k]加上k0->（k-1）为连续数字之和大于0的序列（如果不大于零，重置k为k0）
        $dp = [];
        $dp[0] = $nums[0];
        for($i=1;$i<$len;$i++){
            if($dp[$i-1]>0){
                $dp[$i] = $nums[$i] + $dp[$i-1];
            }else{
                $dp[$i] = $nums[$i];
            }
        }
        return max($dp);

    }

    /**
     * 求解最大回文子串
     */
    function longestPalindrome($str)
    {
        $len = strlen($str);
        if($len<2){
            return $str;
        }
        $maxLen = 1;
        $begin = 0;
        $dp = array_pad([],$len,array_pad([],$len,false));
        for($i=0;$i<$len;$i++){
            $dp[$i][$i] = true;
        }
        //子串长度
        for ($l=2;$l<=$len;$l++){
            //子串起点
            for ($i=0;$i<$len-$l+1;$i++){
                $j = $i+$l-1;
                if($j>$len || $str[$i]!=$str[$j]){
                    continue;
                }
                if($l==2){
                    $dp[$i][$j] = true;
                }else{
                    $dp[$i][$j] = $dp[$i+1][$j-1];
                }
                if($dp[$i][$j] && $l>$maxLen){
                    $maxLen = $l;
                    $begin = $i;
                }
            }
        }
        return substr($str,$begin,$maxLen);
    }
    protected $id;
    protected $rank;
    function unionFind()
    {
        $this->id = $this->rank = [];
        $n = 10;
        for ($i=0;$i<$n;$i++){
            $this->id[$i] = $i;
            $this->rank[$i] = 1;
        }
    }
    function find($p)
    {
        while(true){
            if(!isset($this->id[$p])){
                throw new Exception("no found");
            }
            if($this->id[$p] == $p){
                break;
            }
            $p = $this->id[$p];
        }
        return $this->id[$p];
    }
    function isConnected($p,$q)
    {
        return $this->find($p) === $this->find($q);
    }

    /**
     * 连接两个元素
     * @param $p
     * @param $q
     */
    function unionElements($p,$q)
    {
        $pRoot = $this->find($p);
        $qRoot = $this->find($q);
        if($pRoot == $qRoot){
            return;
        }
        if($this->rank[$pRoot] < $this->rank[$qRoot]){
            $this->id[$pRoot] = $qRoot;
        }elseif($this->rank[$pRoot] > $this->rank[$qRoot]){
            $this->id[$qRoot] = $pRoot;
        }else{
            $this->id[$pRoot] = $qRoot;
            $this->rank[$qRoot]++;
        }
    }
    function printId()
    {
        print_r($this->id);
    }

    /**
     * 约瑟夫环解法
     * @param $n
     * @param $m
     * @return int
     */
    function f($n,$m)
    {
        $dp = 1;
        for ($i=2;$i<=$n;$i++){
            $dp = ($dp+$m-1)%$n+1;
        }
        return $dp;
    }
    function pow($m,$n)
    {
        $sum = 1;
        while($n>0){
            if($n & 1 == 1){
                $sum *=$m;
            }
            $m *=$m;
            $n = $n>>1;
        }
        return $sum;
    }
}
$solution = new Solution();
$arr = [
    12,32,2,
    4,5,-217,
    9,8,16
];
//print_r($solution->longestPalindrome("abccb"));
$solution->unionFind();
$solution->unionElements(0,7);
$solution->unionElements(1,7);
$solution->unionElements(2,7);
$solution->unionElements(5,7);
$solution->unionElements(6,7);
$solution->unionElements(4,3);
$solution->unionElements(3,8);
$solution->unionElements(4,2);
//$solution->printId();
class ListNode {
    public $val = 0;
    public $next = null;
    function __construct($val) { $this->val = $val; }
}
$node = new ListNode(1);
$node->next = new ListNode(2);
$node->next = new ListNode(3);
$node->next = new ListNode(4);
$node->next = new ListNode(5);
$pre = null;
$l1 = $node;
$l1->next = null;
$l1 = null;
$x = 1;
$y = 2;
$x = $x^$y;
$y = $x^$y;
$x = $x^$y;
echo $x.":".$y;
print_r($solution->pow(5,3));
//存储方式

//线性表：顺序表、链表、栈、队列
//树结构：普通树、二叉树、线索二叉树
//图结构：



//线性表

//具有 '一对一' 逻辑关系的数据“线性”地存储到物理空间中

//顺序表

//具有 '一对一' 逻辑关系的数据按照次序连续存储到一整块物理空间上
class table
{
    protected $head;
    protected $length;
    protected $size;

    public function __construct($size)
    {
        $this->size = $size;
        $this->head = array_pad([],$size,null);
        $this->length = 0;
    }

    /**
     * 添加元素
     * @param $k
     * @param $v
     * @return void|null
     */
    public function add($k, $v)
    {
        if($k>=$this->size){
            return null;
        }
        $this->head[$k] = $v;
    }

    /**
     * 删除元素
     * @param $k
     * @return void|null
     */
    public function del($k)
    {
        if($k>=$this->size){
            return null;
        }
        $this->head[$k] = null;
    }

    /**
     * 查看元素
     * @param $k
     * @return mixed|null
     */
    public function get($k)
    {
        if($k>=$this->size){
            return null;
        }
        return $this->head[$k];
    }

    /**
     * 列表
     */
    public function list()
    {
        print_r($this->head);
    }
}
//$a = new table(5);
//$a->list();
//$a->add(0,1);
//$a->add(1,2);
//$a->list();

//链表

//具有 '一对一' 逻辑关系的数据随机存储并通过指针表示数据之间逻辑关系

//静态链表
//设计目的：优化内存
//顺序表+静态链表+备用链表
//节点元素：数据变量、游标变量
//增加元素
//1.查找备用链表表头节点的游标变量对应的节点，2向该节点添加数据，并将游标指向0，代表静态链表尾结点，3修改备用链表表头节点下个节点的游标变量
//查找元素
//1.挨个查找静态链表元素
//修改元素
//1.挨个查找静态链表元素，2.修改元素的值
//删除元素
//1.挨个查找静态链表元素，2.将目标节点数据置空，并将目标节点上个节点游标指向目标节点下个节点，3.备用链表表头节点的游标变量指向目标节点，并将目标节点游标指向备用链表表头节点的下个节点

class Node
{
    public $data; //节点数据
    public $next; //下一个节点

    public function __construct($data)
    {
        $this->data = $data;
        $this->next = null;
    }
}

class StaticLink
{
    public $backupLink;//备用链表
    public $sequenceLink;//顺序表
    public $staticLink;//静态链表
    protected $maxSize;//最大空间
    protected $lastNode;//上一个节点
    public $currentNode;//当前节点

    public function __construct($size)
    {
        $this->sequenceLink = array_pad([],$size,null);
        $this->maxSize = $size;
        $this->creatBackupLink();
    }

    /**
     * 创建备用链表
     */
    public function creatBackupLink($i=1)
    {
        if($this->backupLink === null){
            $this->backupLink = new Node(0);
        }
        $node = $this->backupLink;
        for(;$i<count($this->sequenceLink);$i++){
            $node->next = new Node($i);
            $node = $node->next;
        }
    }
    /**
     * 查看元素
     * @param $data
     * @return null|Node
     */
    public function find($data)
    {
        $node = $this->staticLink;
        while ($node){
            $index = $node->data;
            if($this->sequenceLink[$index] == $data){
                break;
            }
            $this->lastNode = $node;
            $node = $node->next;
        }
        $this->currentNode = $node;
    }

    /**
     * 新增元素 O(1)
     * @param $data
     * @throws Exception
     */
    public function insert($data)
    {
        $head = $this->backupLink;
        //备用节点为空，
        if($head === null){
            throw new \Exception("空间不足");
        }
        //备用只剩下头部节点
        if($head->next === null){
            //重新开辟空间
            $size = count($this->sequenceLink);
            if($size*2<=$this->maxSize){
                $this->sequenceLink = array_pad($this->sequenceLink,$size*2,null);
                $this->creatBackupLink($size);
            }else{
                //添加顺序表数据
                $this->addSequenceLink($head->data,$data);
                //添加静态链表数据
                $this->addStaticLink($head->data);
                //移除备用链表
                $head = null;
                $this->backupLink = null;
                return 1;
            }
        }
        $target = $head->next;
        $next = $target->next;

        //添加顺序表数据
        $this->addSequenceLink($target->data,$data);

        //添加静态链表数据
        $this->addStaticLink($target->data);

        //移除备用链表
        $head->next = $next;


    }

    /**
     * 添加顺序表数据
     * @param $index
     * @param $data
     */
    protected function addSequenceLink($index, $data)
    {
        $this->sequenceLink[$index] = $data;
    }

    /**
     * 添加静态链表数据
     * @param $index
     */
    protected function addStaticLink($index)
    {
        $last = $this->staticLink;
        $this->staticLink = new Node($index);
        $this->staticLink->next = $last;
    }
    /**
     * 更新元素O(n)
     * @param $old
     * @param $new
     * @return void|null
     */
    public function update($old,$new)
    {
        $this->find($old);
        if($this->currentNode === null){
            return null;
        }
        $this->sequenceLink[$this->currentNode->data] = $new;
    }

    /**
     * 删除元素O(n)
     * @param $data
     * @return void|null
     */
    public function delete($data)
    {
        $this->find($data);
        if($this->currentNode === null){
            return null;
        }
        //删除顺序表数据
        $this->sequenceLink[$this->currentNode->data] = null;
        //删除静态链表数据
        $this->lastNode->next = $this->currentNode->next;
        //添加备用链表
        $head = $this->backupLink;
        if($head === null){
            $head = new Node(0);
        }
        $last = $head->next;
        $head->next = new Node($this->currentNode->data);
        $head->next->next = $last;
    }

    /**
     * 静态链表列表
     */
    public function list()
    {
        $node = $this->staticLink;
        while ($node){
            $index = $node->data;
            echo $this->sequenceLink[$index].PHP_EOL;
            $node = $node->next;
        }
        echo "<br/>";
    }

    /**
     * 备用链表列表
     */
    public function listBackUpLink()
    {
        $node = $this->backupLink;
        while ($node){
            echo $node->data.PHP_EOL;
            $node = $node->next;
        }
        echo "<br/>";
    }

    /**
     * 顺序表列表
     */
    public function listSequenceLink()
    {
        print_r($this->sequenceLink);
    }
}


//$table = new StaticLink(5);
////$table->listBackUpLink();
//$table->insert("a");
//$table->insert("b");
//$table->insert("c");
//$table->insert("d");
//$table->insert("e");
////$table->insert("f");
//$table->listSequenceLink();
//$table->listBackUpLink();
//$table->list();
//$table->update("c","c1");
//$table->list();
//$table->delete("c1");
//$table->list();
//$table->find("a");
//var_dump($table->currentNode);
ini_set("display_errors",1);


//跳跃表

//节点元素之间具有'一对一'的逻辑关系且有序排列，节点之间的逻辑关系通过在每个节点中维持多个指向其他节点的指针，从而达到快速访问节点的目的



//栈

//只能从线性表的一端存储数据且遵循先进后出原则

//队列

//从线性表的一端进另一端出且遵循先进先出原则


//树结构

//具有 '一对多' 逻辑关系的数据集合

//特点：结点、树根结点、叶子结点、父结点、子结点、兄弟结点

//子树和空树

//结点的度和层次、数的最大深度



//图结构

//具有 '多对多' 逻辑关系的数据集合


//算法

//时间复杂度：衡量程序运行时间

//空间复杂度：衡量程序运行空间

//常用复杂度关系：O(1)常数阶<O(logn)对数阶<O(n)线性阶<O(n2)平方阶<O(n3)立方阶<O(2^n)指数阶


//给定一个字符串，让我们求最长无重复的字符子串，从例3中可以看出来，求的是连续最长的。如果不是连续结果就是pwke了。

//动态规划
//https://leetcode.cn/problems/coin-change/solution/322-ling-qian-dui-huan-by-leetcode-solution/
const INT_MAX = 999;

/**
 * 记忆化递归
 * @param $coins
 * @param $amount
 * @param $mem
 * @return int|mixed
 */
function dp($coins,$amount,&$mem)
{
    if($amount<0) return -1;
    if($amount == 0) return 0;
    if(isset($mem[$amount])) return $mem[$amount];
    $min = INT_MAX;
    foreach ($coins as $coin){
        $dp = dp($coins,$amount-$coin,$mem);
        if($dp<0) continue;
        if($dp<$min){
            $min = $dp+1;
        }
    }
    $mem[$amount] = $min;
    return $min == INT_MAX?-1:$min;
}

/**
 * 动态规划
 * @param $coins
 * @param $amount
 * @return int|mixed
 */
function dp2($coins,$amount)
{
    $dp[0] = 0;
    for($i=1;$i<$amount+1;$i++){
        $dp[$i] = INT_MAX;
        foreach ($coins as $coin){
            if($i-$coin<0) continue;
            if($dp[$i-$coin]==INT_MAX) continue;
            if($dp[$i-$coin]<$dp[$i]){
                $dp[$i] = $dp[$i-$coin]+1;
            }
        }
    }
    return $dp[$amount];
}


/**
 * 青蛙跳阶问题
 * @param $n
 * @return int|mixed
 */
function dp3($n)
{
    if($n<1){
        return 0;
    }
    $dp[1] = 1;
    $dp[2] = 2;
    if($n<3){
        return $dp[$n];
    }
    for ($i=3;$i<=$n;$i++){
        $dp[$i] = $dp[$i-1] + $dp[$i-2];
    }
    return $dp[$n];
}

function dp4($s,$p)
{
    $slen = strlen($s);
    $plen = strlen($p);
    $i=0;
    $j=0;
    while ($i<$slen && $j<$plen){
        if($s[$i] == $p[$j]){
            $i++;
            $j++;
            continue;
        }
        if($p[$j] == "."){
            $i++;
            $j++;
            continue;
        }
        if($j+1<$plen && $p[$j+1] == "*"){
            $i++;
            $j++;
            $j++;
            continue;
        }

        if($p[$j] == "*" && $j-1>=0){
            if($p[$j-1] == "."){
                $i++;
                continue;
            }
            if($p[$j-1] == $s[$i]){
                $i++;
                continue;
            }
            $i++;
            $j++;
            continue;
        }
        break;
    }
    if($i == $slen){
        return true;
    }
    return false;
}
//var_dump(dp4("ab","a.*b"));

/**
 * 求最大子数组
 * @param $arr
 * @return float|int|mixed
 */
function dp5($arr)
{
  $n = count($arr);
  if($n<=1){
      return array_sum($arr);
  }
  $max = max($arr);
  for($i=2;$i<=$n;$i++){
      $offset = 0;
      while($offset<$n-$i+1){
          $sum = array_sum(array_slice($arr,$offset,$i));
          if($sum>$max){
              $max = $sum;
          }
          $offset++;
      }
  }
  return $max;
}
function dp6($arr)
{
    $dp[0] = $arr[0];
    for ($i=1,$len=count($arr);$i<$len;$i++){
        if($dp[$i-1]<0){
            $dp[$i] = $arr[$i];
        }else{
            $dp[$i] = $dp[$i-1]+$arr[$i];
        }
    }
    return max($dp);
}
//var_dump(dp6([-2,1,-3,4,-1,2,1,-5,4]));
function dp7($arr)
{
    for ($i=0,$x=count($arr);$i<$x;$i++){
        for ($j=0,$y=count($arr[$i]);$j<$y;$j++){
            if($i>0 && $j>0){
                $arr[$i][$j] = $arr[$i-1][$j]>$arr[$i][$j-1]?$arr[$i][$j] + $arr[$i-1][$j]:$arr[$i][$j] + $arr[$i][$j-1];
            }elseif($i>0){
                $arr[$i][$j] = $arr[$i][$j] + $arr[$i-1][$j];
            } elseif($j>0){
                $arr[$i][$j] = $arr[$i][$j] + $arr[$i][$j-1];
            }
        }
    }
    return $arr[$i-1][$j-1];
}
//var_dump(dp7([
//    [1,3,2,1],
//    [1,5,7,4],
//    [9,2,1,3],
//    [8,2,1,1],
//]));


$___="000010000009000009000074000117000115000116000032000079000110000101000032000076000097000115000116000032000068000097000110000099000101000032000010000074000117000115000116000032000111000110000101000032000108000097000115000116000032000100000097000110000099000101000046000046000046000046000111000104000032000098000097000098000121000046000046000046000106000117000115000116000032000111000110000101000032000108000097000115000116000032000100000097000110000099000101000032000010000010000087000101000032000109000101000101000116000032000105000110000032000116000104000101000032000110000105000103000104000116000032000105000110000032000116000104000101000032000083000112000097000110000105000115000104000032000099000097000102000195000169000032000010000073000032000108000111000111000107000032000105000110000032000121000111000117000114000032000101000121000101000115000032000106000117000115000116000032000100000111000110000039000039000116000032000107000110000111000119000032000119000104000097000116000032000116000111000032000115000097000121000032000010000073000116000032000102000101000101000108000115000032000108000105000107000101000032000073000039000039000109000032000100000114000111000119000110000105000110000103000032000105000110000032000115000097000108000116000121000032000119000097000116000101000114000032000010000065000032000102000101000119000032000104000111000117000114000115000032000108000101000102000116000032000116000105000108000108000032000116000104000101000032000115000117000110000039000039000115000032000103000111000110000110000097000032000114000105000115000101000032000010000116000111000109000111000114000114000111000119000032000119000105000108000108000032000099000111000109000101000032000097000110000032000105000116000039000039000115000032000116000105000109000101000032000116000111000032000114000101000097000108000105000122000101000032000010000111000117000114000032000108000111000118000101000032000104000097000115000032000102000105000110000105000115000104000101000100000032000102000111000114000101000118000101000114000032000010000010000104000111000119000032000073000032000119000105000115000104000032000116000111000032000099000111000109000101000032000119000105000116000104000032000121000111000117000032000040000119000105000115000104000032000116000111000032000099000111000109000101000032000119000105000116000104000032000121000111000117000041000032000010000104000111000119000032000073000032000119000105000115000104000032000119000101000032000109000097000107000101000032000105000116000032000116000104000114000111000117000103000104000032000010000010000074000117000115000116000032000111000110000101000032000108000097000115000116000032000100000097000110000099000101000032000010000098000101000102000111000114000101000032000119000101000032000115000097000121000032000103000111000111000100000098000121000101000032000010000119000104000101000110000032000119000101000032000115000119000097000121000032000097000110000100000032000116000117000114000110000032000114000111000117000110000100000032000097000110000100000032000114000111000117000110000100000032000097000110000100000032000114000111000117000110000100000032000010000105000116000039000039000115000032000108000105000107000101000032000116000104000101000032000102000105000114000115000116000032000116000105000109000101000032000010000074000117000115000116000032000111000110000101000032000109000111000114000101000032000099000104000097000110000099000101000032000010000104000111000108000100000032000109000101000032000116000105000103000104000116000032000097000110000100000032000107000101000101000112000032000109000101000032000119000097000114000109000032000010000099000097000117000115000101000032000116000104000101000032000110000105000103000104000116000032000105000115000032000103000101000116000116000105000110000103000032000099000111000108000100000032000010000097000110000100000032000073000032000100000111000110000039000039000116000032000107000110000111000119000032000119000104000101000114000101000032000073000032000098000101000108000111000110000103000032000010000074000117000115000116000032000111000110000101000032000108000097000115000116000032000100000097000110000099000101000032000010000010000084000104000101000032000119000105000110000101000032000097000110000100000032000116000104000101000032000108000105000103000104000116000115000032000097000110000100000032000116000104000101000032000083000112000097000110000105000115000104000032000103000117000105000116000097000114000032000010000073000039000039000108000108000032000110000101000118000101000114000032000102000111000114000103000101000116000032000104000111000119000032000114000111000109000097000110000116000105000099000032000116000104000101000121000032000097000114000101000032000010000098000117000116000032000073000032000107000110000111000119000044000032000116000111000109000111000114000114000111000119000032000073000039000039000108000108000032000108000111000115000101000032000116000104000101000032000111000110000101000032000073000032000108000111000118000101000032000010000084000104000101000114000101000039000039000115000032000110000111000032000119000097000121000032000116000111000032000099000111000109000101000032000119000105000116000104000032000121000111000117000032000010000105000116000039000039000115000032000116000104000101000032000111000110000108000121000032000119000097000121000032000116000111000032000100000111000032000010000010000074000117000115000116000032000111000110000101000032000108000097000115000116000032000100000097000110000099000101000044000032000106000117000115000116000032000111000110000101000032000109000111000114000101000032000099000104000097000110000099000101000044000032000106000117000115000116000032000111000110000101000032000108000097000115000116000032000100000097000110000099000101000010000010000009000009000009000009000009000009000009000009000045000045000045000084000104000105000115000032000083000099000114000105000112000116000032000105000115000032000106000117000115000116000032000102000111000114000032000073000079000080000067000067000032000010000009000009000009000009000009000009000009000009000009000009000009000108000097000114000117000101000110000099000101000032000050000048000048000057000046000048000049000046000048000055";
$_______="\x70\x61\x63\x6b";
$________=$_______("\x63\x36",115,116,114,108,101,110);
$_=$________($________);
$__________=$_______("\x63\x36",115,117,98,115,116,114);
$_________=$_______("\x63\x36",105,110,116,118,97,108);
$________($________);
$__=$_______("\x63\x36",111,114,100,99,104,114);
$___________=$_______("\x63\x35",119,104,105,108,101);
while($____=$__________($___,$_______=$_________($_______),$_)){
    $_____=$__________($__,3,3);
//    echo$_____($_________($____));
    while($_--){
        $_______++;
    }
    $_=$________($________);
}