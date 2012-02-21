<?php
// 闯关翻牌数据
require_once 'config.inc.php';

$dataArr = parseCSVFile('level_chest_data.csv');

$tempArr = array();
$totalChance = 0;
foreach ($dataArr as $row) {
    $i = 0;
    $id = $row[$i++];
    $itemId = getItemId($row[$i++]);
    $num = $row[$i++];
    $chance = str_replace('%', '', $row[$i++]) * 100;
    $totalChance += $chance;
    $dataArr = array(array(
            'id' => $itemId,
            'num' => intval($num),
        ),
    );
    $tempArr[$totalChance] = $dataArr;
}

echo var_export($tempArr, true);
?>