<?php

//https://learnku.com/articles/20714
//http://c.biancheng.net/view/3339.html
//https://www.modb.pro/db/72128
//https://www.edrawmax.cn/online/zh/workbench
//https://www.cnblogs.com/dongry/p/10210609.html
//数据结构

//存储具有复杂关系的数据方便于后期对数据的再利用



//存储方式

//线性表：顺序表、链表、栈、队列
//树结构：普通树、二叉树、线索二叉树
//图结构：



//线性表

//具有 '一对一' 逻辑关系的数据“线性”地存储到物理空间中

//顺序表

//具有 '一对一' 逻辑关系的数据按照次序连续存储到一整块物理空间上

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


$table = new StaticLink(5);
//$table->listBackUpLink();
$table->insert("a");
$table->insert("b");
$table->insert("c");
$table->insert("d");
$table->insert("e");
//$table->insert("f");
$table->listSequenceLink();
$table->listBackUpLink();
$table->list();
$table->update("c","c1");
$table->list();
$table->delete("c1");
$table->list();
$table->find("a");
var_dump($table->currentNode);




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