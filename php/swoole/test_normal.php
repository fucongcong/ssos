<?php

for($i=0;$i<=5;$i++) {

    $test[] = [$i, $i+1];
}
function doNoYield(&$test) {

    foreach ($test as &$value) {

        $value++;
    }
}
doNoYield($test);

foreach ($test as $value) {

    sleep(2);
}

