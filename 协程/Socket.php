<?php

echo "------------------- Begin Use Socket   ---------------------------" . "\n";

class Task
{
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

    public function __construct($taskId, Generator $coroutine)
    {
        $this->taskId = $taskId;
        $this->coroutine = $coroutine;
    }

    public function getTaskId()
    {
        return $this->taskId;
    }

    public function setSendValue($sendValue)
    {
        $this->sendValue = $sendValue;
    }

    public function run()
    {
        if ($this->beforeFirstYield) {
            $this->beforeFirstYield = false;
            return $this->coroutine->current();
        } else {
            $retval = $this->coroutine->send($this->sendValue);
            $this->sendValue = null;
            return $retval;
        }
    }

    public function isFinished()
    {
        return !$this->coroutine->valid();
    }
}

class Scheduler
{
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

    public function __construct()
    {
        $this->taskQueue = new SplQueue();
    }

    public function newTask(Generator $coroutine)
    {
        $tid = ++$this->maxTaskId;
        $task = new Task($tid, $coroutine);
        $this->taskMap[$tid] = $task;
        $this->schedule($task);
        return $tid;
    }

    public function killTask($tid)
    {
        if (!isset($this->taskMap[$tid])) {
            return false;
        }
        unset($this->taskMap[$tid]);
        // This is a bit ugly and could be optimized so it does not have to walk the queue,
        // but assuming that killing tasks is rather rare I won't bother with it now
        foreach ($this->taskQueue as $i => $task) {
            if ($task->getTaskId() === $tid) {
                unset($this->taskQueue[$i]);
                break;
            }
        }
        return true;
    }

    public function schedule(Task $task)
    {
        $this->taskQueue->enqueue($task);
    }
    protected $waitingForRead = [];
    protected $waitingForWrite = [];
    public function waitForRead($socket, Task $task) {
        if (isset($this->waitingForRead[(int) $socket])) {
            $this->waitingForRead[(int) $socket][1][] = $task;
        } else {
            $this->waitingForRead[(int) $socket] = [$socket, [$task]];
        }
    }
    public function waitForWrite($socket, Task $task) {
        if (isset($this->waitingForWrite[(int) $socket])) {
            $this->waitingForWrite[(int) $socket][1][] = $task;
        } else {
            $this->waitingForWrite[(int) $socket] = [$socket, [$task]];
        }
    }
    protected function ioPoll($timeout) {
        $rSocks = [];
        foreach ($this->waitingForRead as list($socket)) {
            $rSocks[] = $socket;
        }
        $wSocks = [];
        foreach ($this->waitingForWrite as list($socket)) {
            $wSocks[] = $socket;
        }
        $eSocks = []; // dummy
        if (!stream_select($rSocks, $wSocks, $eSocks, $timeout)) {
            return;
        }
        foreach ($rSocks as $socket) {
            list(, $tasks) = $this->waitingForRead[(int) $socket];
            unset($this->waitingForRead[(int) $socket]);
            foreach ($tasks as $task) {
                $this->schedule($task);
            }
        }
        foreach ($wSocks as $socket) {
            list(, $tasks) = $this->waitingForWrite[(int) $socket];
            unset($this->waitingForWrite[(int) $socket]);
            foreach ($tasks as $task) {
                $this->schedule($task);
            }
        }
    }
    protected function ioPollTask() {
        while (true) {
            if ($this->taskQueue->isEmpty()) {
                $this->ioPoll(null);
            } else {
                $this->ioPoll(0);
            }
            yield;
        }
    }
    public function run()
    {
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

class SystemCall
{
    protected $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function __invoke(Task $task, Scheduler $scheduler)
    {
        $callback = $this->callback;
        return $callback($task, $scheduler);
    }
}

function getTaskId()
{
    return new SystemCall(function (Task $task, Scheduler $scheduler) {
        $task->setSendValue($task->getTaskId());
        $scheduler->schedule($task);
    });
}

function newTask(Generator $coroutine)
{
    return new SystemCall(
        function (Task $task, Scheduler $scheduler) use ($coroutine) {
            $task->setSendValue($scheduler->newTask($coroutine));
            $scheduler->schedule($task);
        }
    );
}

function killTask($tid)
{
    return new SystemCall(
        function (Task $task, Scheduler $scheduler) use ($tid) {
            $task->setSendValue($scheduler->killTask($tid));
            $scheduler->schedule($task);
        }
    );
}

function childTask()
{
    $tid = (yield getTaskId());
    while (true) {
        echo "Child task $tid still alive!\n";
        yield;
    }
}

function task()
{
    $tid = (yield getTaskId());
    $childTid = (yield newTask(childTask()));
    for ($i = 1; $i <= 6; ++$i) {
        echo "Parent task $tid iteration $i.\n";
        yield;
        if ($i == 3) yield killTask($childTid);
    }
}
function waitForRead($socket) {
    return new SystemCall(
        function(Task $task, Scheduler $scheduler) use ($socket) {
            $scheduler->waitForRead($socket, $task);
        }
    );
}
function waitForWrite($socket) {
    return new SystemCall(
        function(Task $task, Scheduler $scheduler) use ($socket) {
            $scheduler->waitForWrite($socket, $task);
        }
    );
}
function server($port) {
    echo "Starting server at port $port...\n";
    $socket = @stream_socket_server("tcp://swoole-php:$port", $errNo, $errStr);
    if (!$socket) throw new Exception($errStr, $errNo);
    stream_set_blocking($socket, 0);
    while (true) {
        yield waitForRead($socket);
        $clientSocket = stream_socket_accept($socket, 0);
        yield newTask(handleClient($clientSocket));
    }
}
function handleClient($socket) {
    yield waitForRead($socket);
    $data = fread($socket, 8192);
    $msg = "Received following request:\n\n$data";
    $msgLength = strlen($msg);
    $response = "<<<res
HTTP/1.1 200 OK\r
Content-Type: text/plain\r
Content-Length: $msgLength\r
Connection: close\r
\r
$msg
RES";
    yield waitForWrite($socket);
    fwrite($socket, $response);
    fclose($socket);
}
$scheduler = new Scheduler;
$scheduler->newTask(server(9003));
$scheduler->run();

echo "------------------- End Use Socket   ---------------------------" . "\n";