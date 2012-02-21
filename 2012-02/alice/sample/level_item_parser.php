<?php
// 关卡奖励道具
require_once 'config.inc.php';

$dataArr = parseCSVFile('level_item.csv');

$tempArr = array();
foreach ($dataArr as $row) {
    $i = 0;
    $areaId = getAreaId($row[$i++]);

    $nameArr = explode(',', $row[$i++]);
    $idArr = array();
    foreach ($nameArr as $name) {
        $idArr[] = getItemId($name);
    }
    $tempArr[$areaId][1] = array(implode(', ', $idArr));

    $nameArr = explode(',', $row[$i++]);
    $idArr = array();
    foreach ($nameArr as $name) {
        $idArr[] = getItemId($name);
    }
    $tempArr[$areaId][7] = array(implode(', ', $idArr));

    $nameArr = explode(',', $row[$i++]);
    $idArr = array();
    foreach ($nameArr as $name) {
        $idArr[] = getItemId($name);
    }
    $tempArr[$areaId][8] = array(implode(', ', $idArr));

    $nameArr = explode(',', $row[$i++]);
    $idArr = array();
    foreach ($nameArr as $name) {
        $idArr[] = getItemId($name);
    }
    $tempArr[$areaId][4] = array(implode(', ', $idArr));

//    $nameArr = explode(',', $row[$i++]);
//    $idArr = array();
//    foreach ($nameArr as $name) {
//        $idArr[] = getItemId($name);
//    }
//    $tempArr[$areaId][13] = array(implode(', ', $idArr));
//
//    $nameArr = explode(',', $row[$i++]);
//    $idArr = array();
//    foreach ($nameArr as $name) {
//        $idArr[] = getItemId($name);
//    }
//    $tempArr[$areaId][10] = array(implode(', ', $idArr));
}
echo var_export($tempArr, true);
?>