<?php

require_once ('pinyin/Pinyin.php');

$res = [

  [
    'real_name' => '哈哈',
    'num' => 1,
  ],
  [
    'real_name' => 'o哈哈',
    'num' => 1,
  ]

];

$sort = [];
$numsort = [];
$diffsort = [];

Pinyin::set('delimiter', '');
Pinyin::set('accent', false);
Pinyin::set('uppercase', true);

$dict = array();

foreach ($res as $value) {

    $workName = $value['real_name'];

    if(isset($dict[$workName])) {

        $value['pinyin'] = $dict[$workName]['pinyin'];
        $key = $dict[$workName]['key'];

    }else {

        $value['pinyin'] = Pinyin::trans($value['real_name']);
        $key = getFirstchar($value['pinyin']);
        $dict[$workName]['pinyin'] = $value['pinyin'];
        $dict[$workName]['key'] = $key;

    }

    $value['num'] = intval($value['num']);

    unset($value['value']);

    $id = $value['pinyin'];
    if($key == '#') {

        if(isset($diffsort[$key][$id])) {

            $diffsort[$key][$id]['num'] = $diffsort[$key][$id]['num'] + $value['num'];
        }else {

            $diffsort[$key][$id] = $value;
        }


    }elseif($key == '0-9') {

        if(isset($numsort[$key][$id])) {

            $numsort[$key][$id]['num'] = $numsort[$key][$id]['num'] + $value['num'];
        }else {

            $numsort[$key][$id] = $value;
        }

    }else{

        if(isset($sort[$key][$id])) {

            $sort[$key][$id]['num'] = $sort[$key][$id]['num'] + $value['num'];
        }else {

            $sort[$key][$id] = $value;
        }
    }

}

ksort($sort);
$sort = array_merge($sort, $numsort, $diffsort);

$pinyin = $sort;

foreach($pinyin as &$item) {

    ksort($item);

}

var_dump($pinyin);

function getFirstchar($str){

    $str = trim(preg_replace('/\W|_/', '', $str));
    $fchar = mb_substr($str,0,1,'utf-8');

    if(in_array($fchar, ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
        'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'])) {

        return strtoupper($fchar);
    }

    if(in_array($fchar, ['0','1','2','3','4','5','6','7','8','9'])) {

        return '0-9';
    }

    return '#';
}
