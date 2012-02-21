<?php
// 合成材料
require_once 'config.inc.php';

$dataArr = parseCSVFile('material_data.csv');

$tempArr = array();
$tempArr2 = array();
foreach ($dataArr as $row) {
    $i = 0;
    $areaName = $row[$i++];
    $node = $row[$i++];
    $itemNameStr = $row[$i++];
    $node = str_replace('--', '-', $node);
    if ($itemNameStr != '') {
        $itemNameArr = explode(',', $itemNameStr);
        $itemIdArr = array();
        foreach ($itemNameArr as $name) {
            $itemId = getItemId($name);
            if (!is_numeric($itemId)) {
                echo $itemId . '<br />';
            }
            $itemIdArr[] = intval($itemId);
        }
        $tempArr[$node] = $itemIdArr;
        $tempArr2[$node] = '掉落的合成材料：<font color="#0033ff">' . $itemNameStr . "</font>";
    } else {
        $tempArr2[$node] = '';
    }
}


echo var_export($tempArr, true);
echo '<br />';
echo json_encode($tempArr2);
?>
