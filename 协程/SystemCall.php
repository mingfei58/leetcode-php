<?php
echo "------------------- Begin Use SystemCall   ---------------------------"."\n";
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
            $retval = $task->run();
            if ($retval instanceof SystemCall) {
                $retval($task, $this);
                continue;
            }
            if ($task->isFinished()) {
                unset($this->taskMap[$task->getTaskId()]);
            } else {
                $this->schedule($task);
            }
        }
    }
}
class SystemCall {
    protected $callback;
    public function __construct(callable $callback) {
        $this->callback = $callback;
    }
    public function __invoke(Task $task, Scheduler $scheduler) {
        $callback = $this->callback;
        return $callback($task, $scheduler);
    }
}
function getTaskId() {
    return new SystemCall(function(Task $task, Scheduler $scheduler) {
        $task->setSendValue($task->getTaskId());
        $scheduler->schedule($task);
    });
}

function task($max) {
    $tid = (yield getTaskId()); // <-- here's the syscall!
    for ($i = 1; $i <= $max; ++$i) {
        echo "This is task $tid iteration $i.\n";
        yield;
    }
}
$scheduler = new Scheduler();
$scheduler->newTask(task(5));
$scheduler->newTask(task(10));
$scheduler->run();
echo "------------------- End Use SystemCall   ---------------------------"."\n";
