<?php
// 果树升级条件
require_once 'config.inc.php';

$dataArr = parseCSVFile('farm_config.csv');

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
        'result' => array(
            1 => array('id' => 7901, 'num' => intval($row[$i++])),
            2 => array('id' => 7901, 'num' => intval($row[$i++])),
            3 => array('id' => 7901, 'num' => intval($row[$i++])),
            4 => array('id' => 7901, 'num' => intval($row[$i++])),
            5 => array('id' => 7901, 'num' => intval($row[$i++])),
            6 => array('id' => 7901, 'num' => intval($row[$i++])),
        ),
    );
    unset($tempArr[$level]['status']);
}

echo var_export($tempArr, true);
echo '<br />';
echo json_encode($tempArr);
?>
