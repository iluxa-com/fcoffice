<?php
// 关卡结束翻牌系统的数据解析
require_once 'config.inc.php';

$dataArr = parseCSVFile('level_chest_data.csv');

$totalChance = 0;
$tempArr = array();
foreach ($dataArr as $row) {
    $name = $row[0];
    $itemId = getItemId($name);
    if (!is_numeric($itemId)) {
        echo "{$itemId} not exits<br />";
    }
    $num = $row[1];
    $totalChance += str_replace('%', '', $row[2]) * 100;
    $tempArr[$totalChance] = array(
        'id' => intval($itemId),
        'num' => intval($num),
    );
}
echo var_export($tempArr, true);
?>
