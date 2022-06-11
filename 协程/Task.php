<?php
echo "------------------- Begin Task   ---------------------------"."\n";

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
$task1 = new Task(1,task1());
$task1->run();

$task2 = new Task(1,task2());
$task2->run();



echo "------------------- End Task   ---------------------------"."\n";