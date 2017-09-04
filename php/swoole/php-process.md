####最近跑的定时任务由于数据量较大，导致单个php文件跑的时间会比较长。于是就尝试如何优化

#####思路：在启动PHP进程是，同时开启N个子进程进行处理。这样理想情况下，时间将会缩短N倍。但是考虑到CPU核数与内存使用量，暂定为4个进程。

#####开启写测试脚本

    脚本1，正常情况下

    <?php
    //初始化了一个数组，线上可能会很大
    for($i=0;$i<=5;$i++) {

        $test[] = [$i, $i+1];
    }

    //处理了一下数据
    function doNoYield(&$test) {

        foreach ($test as &$value) {

            $value++;
        }
    }

    doNoYield($test);

    //进行业务逻辑处理，这里将会比较耗时，我模拟每一次处理需要2S
    foreach ($test as $value) {
        sleep(2);
    }

#####最后跑得结果

    real    0m12.049s
    user    0m0.020s
    sys 0m0.006s




    脚本2，使用多进程，协程处理

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
                if ($data != false){
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

#####最后跑得结果

    real 0m4.033s
    user 0m0.023s
    sys 0m0.011s

#####比较可以发现速度会加快3倍，当然处理的业务越复杂时，后者优势越明显。毕竟使用后者，需要更多的内存。如果一个脚本跑的速度非常快，那么使用后者，反而会更慢。





