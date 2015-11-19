<?php

function doYield($test) {

    foreach ($test as $value) {

        $value++;
        yield $value;
    }
}

for($i=0;$i<=5;$i++) {

    $test[] = [$i, $i+1];
}
$gen = doYield($test);

function dotest() {

    global $gen;
    // $obj = new ArrayObject( $test );
    // $gen = $obj->getIterator();

    if ($gen -> valid()){

        $data = $gen -> current();
        // $key = $gen -> key();
        $gen -> next();
        return json_encode($data);
    }else {
        return false;
    }

}

function dosomething($data) {

    //具体要子进程处理的逻辑 如果这里的处理时间越长，使用多进程处理会越显优势
    sleep(2);
    return 1;
}

//启动4个进程进行处理
$worker_num = 4;
for($i=0; $i<$worker_num ;$i++) {

    $process = new swoole_process('callback_function', false, true);
    $pid = $process->start();
    $process->write(dotest());
    $process->pid = $pid;
    // $workers[$pid] = $process;

    swoole_event_add($process->pipe, function($pipe) use ($process) {

        $recv = $process->read();
        if ($recv != '') {
            $data = dotest();
            if ($data !== false){
                $process->write($data);
            }else{
                swoole_process::kill($process->pid);
            }
        }
    });
}

//开启子进程进行异步处理
function callback_function(swoole_process $worker)
{
    $GLOBALS['worker'] = $worker;
    swoole_event_add($worker->pipe, function($pipe) {
        $worker = $GLOBALS['worker'];
        $recv = $worker->read();

        if($recv) {
            $data = call_user_func_array('dosomething', [json_decode($recv,true)]);
            $worker->write($data);
        }else {

            $worker->write('');
        }
    });

}

swoole_process::signal(SIGCHLD, function ($signo) use ($worker_num) {
    static $worker_count = 0;
    while($ret =  swoole_process::wait(false)) {
        echo 'Worker Exit, pid=', $ret['pid'], PHP_EOL;
        $worker_count++;
        if ($worker_count >= $worker_num){
            echo memory_get_usage(true)/1024/1024, PHP_EOL;
            swoole_event_exit();
        }
    }
});





