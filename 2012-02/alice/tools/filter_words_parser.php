<?php
$dataStr = file_get_contents('filter_words_origin.lst');

$dataArr = preg_split('/[,，、\r\n]+/u', $dataStr, -1, PREG_SPLIT_NO_EMPTY);
// 空白字符处理
foreach ($dataArr as &$val) {
    $val = trim($val);
}
// 去重复处理
$tempArr = array();
foreach ($dataArr as $val2) {
    $tempArr[$val2] = '';
}
file_put_contents('filter_words.lst', implode("\n", array_keys($tempArr)));
?>