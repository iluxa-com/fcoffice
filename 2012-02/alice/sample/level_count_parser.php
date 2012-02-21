<?php
require_once 'config.inc.php';

$dataArr = parseCSVFile('level_count.csv');

$tempArr = array();
foreach ($dataArr as $row) {
    $i = 0;
    $areaId = $row[$i++];
    if (!is_numeric($areaId)) {
        continue;
    }
    $needGrade = $row[$i++];
    $needEnergy = $row[$i++];
    $difficulty = $row[$i++];
    $exp = $row[$i++];
    $length = $row[$i++];
    $itemCount = $row[$i++];
    $silver = $row[$i++];
    $levelCount = $row[$i++];

    $skill = 0;
    $key = implode('-', array($areaId, $skill, $difficulty));
    $tempArr[$key] = intval($levelCount);
}
echo var_export($tempArr, true);
echo "\n";
echo json_encode($tempArr);
?>