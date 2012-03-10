<?php
$arr_test = array(
    'str1' => '陈小明1985',
    'str2' => '陈小明 1985',
    'str3' => '陈小明',
    'str4' =>'陈小明abcd123',
    'str5' =>'陈小abc明12cc',
);
function split_words($str) {
    if(preg_match('#(.+?)(\w+)$#',$str,$matches) ) {
        array_shift($matches);
        return array_map('trim',$matches);
    }else {
        return array($str,);
    }
 }   
 
 $result = array_map('split_words',$arr_test);
 var_dump($result);
    
    
    