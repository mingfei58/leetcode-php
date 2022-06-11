<?php
echo "------------------- Begin Scheduler   ---------------------------"."\n";

class Task {
    /**
     * 任务id
     * @var
     */
    protected $taskId;
    /**
     * 迭代器
     * @var Generator
     */
    protected $coroutine;
    /**
     * 设置yeild表达式返回值
     * @var null
     */
    protected $sendValue = null;
    /**
     * 是否首次执行
     * @var bool
     */
    protected $beforeFirstYield = true;
    public function __construct($taskId, Generator $coroutine) {
        $this->taskId = $taskId;
        $this->coroutine = $coroutine;
    }
    public function getTaskId() {
        return $this->taskId;
    }
    public function setSendValue($sendValue) {
        $this->sendValue = $sendValue;
    }
    public function run() {
        if ($this->beforeFirstYield) {
            $this->beforeFirstYield = false;
            return $this->coroutine->current();
        } else {
            $retval = $this->coroutine->send($this->sendValue);
            $this->sendValue = null;
            return $retval;
        }
    }
    public function isFinished() {
        return !$this->coroutine->valid();
    }
}

class Scheduler {
    /**
     * 任务id
     * @var int
     */
    protected $maxTaskId = 0;
    /**
     * 任务列表
     * @var array
     */
    protected $taskMap = []; // taskId => task
    /**
     * 队列管理器
     * @var SplQueue
     */
    protected $taskQueue;
    public function __construct() {
        $this->taskQueue = new SplQueue();
    }
    public function newTask(Generator $coroutine) {
        $tid = ++$this->maxTaskId;
        $task = new Task($tid, $coroutine);
        $this->taskMap[$tid] = $task;
        $this->schedule($task);
        return $tid;
    }
    public function schedule(Task $task) {
        $this->taskQueue->enqueue($task);
    }
    public function run() {
        while (!$this->taskQueue->isEmpty()) {
            $task = $this->taskQueue->dequeue();
            $task->run();
            if ($task->isFinished()) {
                unset($this->taskMap[$task->getTaskId()]);
            } else {
                $this->schedule($task);
            }
        }
    }
}
function task1() {
    for ($i = 1; $i <= 10; ++$i) {
        echo "This is task 1 iteration $i.\n";
        yield;
    }
}
function task2() {
    for ($i = 1; $i <= 5; ++$i) {
        echo "This is task 2 iteration $i.\n";
        yield;
    }
}
$scheduler = new Scheduler();
$scheduler->newTask(task1());
$scheduler->newTask(task2());
$scheduler->run();
echo "------------------- End Scheduler   ---------------------------"."\n";