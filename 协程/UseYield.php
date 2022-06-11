<?php
echo "------------------- Begin Use Yield   ---------------------------"."\n";
//eq 1
//yeild 迭代值
function xrange($start, $end, $step = 1) {
    for ($i = $start; $i <= $end; $i += $step) {
        yield $i;
    }
}
$range = xrange(1, 1000000);
var_dump($range); // object(Generator)#1
var_dump($range instanceof Iterator); // bool(true)



//eq 2
//yield 传值
function logger($fileName) {
    $fileHandle = fopen($fileName, 'a');
    while (true) {
        fwrite($fileHandle, yield . "\n");
    }
}
$logger = logger(__DIR__ . '/log');
$logger->send('Foo');
$logger->send('Bar');

//eq 3
//yield 传值、迭代
function gen() {
    $ret = (yield 'yield1');
    var_dump($ret);
    $ret = (yield 'yield2');
    var_dump($ret);
}
$gen = gen();
//Sets the return value of the yield expression and resumes the generator
$next = $gen->send("ret1");
var_dump($next);
$next = $gen->send("ret2");
var_dump($next);
echo "------------------- End Use Yield   ---------------------------"."\n";

