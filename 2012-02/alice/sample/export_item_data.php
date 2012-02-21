<?php
require_once 'config.inc.php';

$itemDataSQLModel = new ItemDataSQLModel();
$orderByArr = array(
    'item_id' => 'ASC',
);
$rowArr = $itemDataSQLModel->SH()->orderBy($orderByArr)->getAll();
$tempArr = array();
foreach ($rowArr as $row) {
    $itemId = intval($row['item_id']);
    $itemName = $row['item_name'];
    $linkName = $row['link_name'];
    //$filename = $row['filename'];
    $extraInfoStr = $row['extra_info'];
    $description = $row['description'];
    $buyable = intval($row['buyable']);
    $useable = intval($row['useable']);
    $clickable = intval($row['clickable']);
    $dbclickable = intval($row['dbclickable']);
    $dragable = intval($row['dragable']);
    $grade = intval($row['grade']);

    $extraInfoArr = ($extraInfoStr === '') ? array() : json_decode($extraInfoStr, true);
    $extraInfoArr['item_id'] = $itemId;
    $extraInfoArr['item_name'] = $itemName;
    $extraInfoArr['link_name'] = $linkName;
    //$extraInfo['filename'] = $filename;
    $extraInfoArr['description'] = $description;
    $extraInfoArr['if_buy'] = $buyable; // AS中要求用if_buy
    $extraInfoArr['useable'] = $useable;
    $extraInfoArr['clickable'] = $clickable;
    $extraInfoArr['dbclickable'] = $dbclickable;
    $extraInfoArr['dragable'] = $dragable;
    $extraInfoArr['grade'] = $grade;
    $tempArr[$itemId] = $extraInfoArr;
}
$dataStr = json_encode($tempArr);
download('item_data.json', $dataStr);
?>