<?php
// 新手任务奖励解析脚本
require_once 'config.inc.php';

$dataArr = parseCSVFile('init_task_reward.csv');

$tempArr = array();
foreach ($dataArr as $row) {
    $i = 0;
    $taskId = $row[$i++];
    if (!is_numeric($taskId)) {
        continue;
    }
    // 跳过三列
    $i++;
    $i++;
    $i++;
    $rewardArr = array();
    $name = $row[$i++];
    if ($name != '') {
        $itemId = getItemId($name);
        $num = $row[$i++];
        $rewardArr[] = array(
            'id' => intval($itemId),
            'num' => intval($num),
        );
    }
    $name = $row[$i++];
    if ($name != '') {
        $itemId = getItemId($name);
        $num = $row[$i++];
        $rewardArr[] = array(
            'id' => intval($itemId),
            'num' => intval($num),
        );
    }
    $tempArr[$taskId] = $rewardArr;
}

echo var_export($tempArr, true);
?>