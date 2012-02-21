<?php
// 马房配置数据解析脚本
require_once 'config.inc.php';

$dataArr = parseCSVFile('horse_config.csv');

$tempArr = array();
$tempArr2 = array();
foreach ($dataArr as $row) {
    $i = 0;
    $level = $row[$i++];
    if (!is_numeric($level)) {
        continue;
    }
    $tempArr[$level] = array(
        'grade' => intval($row[$i++]),
        'need' => array(
            array('id' => 7901, 'num' => intval($row[$i++])),
        ),
    );
    $i++;
    $tempArr2[$level] = array(
        1 => intval($row[$i++]),
        2 => intval($row[$i++]),
        3 => intval($row[$i++]),
        4 => intval($row[$i++]),
        5 => intval($row[$i++]),
        6 => intval($row[$i++]),
    );
}

echo var_export($tempArr, true);
echo '<br />';
echo json_encode($tempArr);
echo '<br />';
echo '<br />';
echo var_export($tempArr2, true);
echo '<br />';
echo json_encode($tempArr2);
?>