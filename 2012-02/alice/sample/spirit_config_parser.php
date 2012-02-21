<?php
// 精灵屋配置数据解析脚本
require_once 'config.inc.php';

$dataArr = parseCSVFile('spirit_config.csv');

$tempArr = array();
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
        'status' => $row[$i++],
        'silver' => intval($row[$i++]),
    );
    unset($tempArr[$level]['status']);
}

echo var_export($tempArr, true);
echo '<br />';
echo json_encode($tempArr);
?>