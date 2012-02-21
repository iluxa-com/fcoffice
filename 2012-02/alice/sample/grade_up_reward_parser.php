<?php
// 升级奖励
require_once 'config.inc.php';

$dataArr = parseCSVFile('grade_up_reward.csv');

$tempArr = array();
foreach ($dataArr as $row) {
    $grade = $row[0];
    if (!is_numeric($grade)) {
        continue;
    }
    $rewardArr = array();
    $name = $row[1];
    $num = $row[2];
    $itemId = getItemId($name);
    if (!is_numeric($itemId)) {
        echo "{$itemId} not exists<br />";
    }
    $rewardArr[] = array('id' => intval($itemId), 'num' => intval($num));
    $name = $row[3];
    $num = $row[4];
    $itemId = getItemId($name);
    if (!is_numeric($itemId)) {
        echo "{$itemId} not exists<br />";
    }
    $rewardArr[] = array('id' => intval($itemId), 'num' => intval($num));

    $tempArr[$grade] = $rewardArr;
}

echo var_export($tempArr, true);
?>