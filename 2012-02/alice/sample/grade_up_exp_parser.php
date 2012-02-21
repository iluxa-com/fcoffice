<?php
// 升级累积经验
require_once 'config.inc.php';

$dataArr = parseCSVFile('grade_up_exp.csv');

$tempArr = array();
$tempArr2 = array();
$tempArr3 = array();
foreach ($dataArr as $row) {
    $grade = $row[0];
    if (!is_numeric($grade)) {
        continue;
    }
    $exp = $row[3];
    $tempArr[$grade] = intval($exp);
    $tempArr2[$exp] = intval($grade);
    $tempArr3[] = array('grade' => intval($grade), 'exp' => intval($exp));
}

echo var_export($tempArr, true);
echo '<br />';
echo var_export($tempArr2, true);
echo '<br />';
echo json_encode($tempArr3);
?>