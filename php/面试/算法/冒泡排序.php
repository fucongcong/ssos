<?php 
//算法复杂度为 n^2

$data = [2, 5, 1, 3, 5, 7, 0, 2, 5, 1, 3, 7, 5];

function pSort($data) {
    $num = count($data);

    for ($j=0; $j < $num; $j++) {
        for ($i=0; $i < $num - $j - 1; $i++) {
            if (isset($data[$i + 1]) && $data[$i] > $data[$i + 1]) {
                $tmp = $data[$i];
                $data[$i] = $data[$i + 1];
                $data[$i + 1] = $tmp;
            }
        }
    }
    return $data;
}


$data = pSort($data);
print_r($data);



