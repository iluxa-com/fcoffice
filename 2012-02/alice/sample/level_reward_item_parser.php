<?php
require_once 'config.inc.php';

$filename = 'level_reward_item.csv';
$dataStr = file_get_contents($filename);
$dataStr = iconv('GBK', 'UTF-8', $dataStr);

$csv = new CSV();
$dataArr = $csv->restoreData($dataStr);

$tempArr = array();
$totalChance = 0;
foreach ($dataArr as $row) {
    $itemId = getItemId($row[0]);
    if (!is_numeric($itemId)) {
        echo $itemId . '<br />';
        continue;
    }
    $chance = str_replace('%', '', $row[1]) * 100;
    $totalChance += $chance;
    $tempArr[$totalChance] = array(
        'id' => intval($itemId),
        'num' => 1,
    );
}

echo var_export($tempArr, true);
?>