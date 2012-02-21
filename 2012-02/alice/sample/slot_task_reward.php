<?php

// 发布任务奖励
require_once 'config.inc.php';

$dataArr = parseCSVFile('slot_task_reward.csv');

$tempArr = array();
$totalChance = 0;
foreach ($dataArr as $row) {
    $i = 0;
    $i++; // 跳过方案编号
    $arr = array();
    $itemId = getItemId($row[$i++]);
    if (!is_numeric($itemId)) {
        echo $itemId . '<br />';
        continue;
    }
    $num = $row[$i++];
    $arr[] = array(
        'id' => intval($itemId),
        'num' => intval($num),
    );
    $itemId = getItemId($row[$i++]);
    if (!is_numeric($itemId)) {
        echo $itemId . '<br />';
        continue;
    }
    $num = $row[$i++];
    $arr[] = array(
        'id' => intval($itemId),
        'num' => intval($num),
    );
    $chance = str_replace('%', '', $row[$i++]) * 100;
    $totalChance += $chance;
    $tempArr[$totalChance] = $arr;
}

echo var_export($tempArr, true);
?>