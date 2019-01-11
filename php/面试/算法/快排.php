<?php 
//算法复杂度为 nlog(n)

$data = [2, 5, 1, 3, 5, 7, 0, 2, 5, 1, 3, 5, 7];

function qSort($data) {
    if (count($data) <= 1) {
        return $data;
    }

    $first = $data[0];
    $left = [];
    $right = [];
    for ($i=1; $i < count($data); $i++) { 
        if ($data[$i] > $first) {
            $right[] = $data[$i];
        } else {
            $left[] = $data[$i];
        }
    }

    $right = qSort($right);
    $left = qSort($left);

    return array_merge($left, [$first], $right);
}

$res = qSort($data);
