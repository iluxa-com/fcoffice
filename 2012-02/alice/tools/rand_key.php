<?php
/**
 * 生成一个指定长度的随机Key
 * @param int $len 长度
 * @return string
 */
function genRandKey($len) {
    $charStr = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $maxIndex = strlen($charStr) - 1;
    $key = '';
    while($len-- > 0) {
        $key .= $charStr[rand(0, $maxIndex)];
    }
    return $key;
}

echo genRandKey(32);
?>