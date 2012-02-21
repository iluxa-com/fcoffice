<?php
// 魔法果树果子奖励配置数据解析脚本
require_once 'config.inc.php';

$dataArr = parseCSVFile('tree_reward.csv');

$tempArr = array();
foreach ($dataArr as $row) {
    $i = 0;
    $level = $row[$i++];
    if (!is_numeric($level)) {
        continue;
    }
    $i++;
    $tempArr[$level] = array(
        3333 => array(array('id' => 7902, 'num' => intval($row[$i++]))),
        6666 => array(array('id' => 7901, 'num' => intval($row[$i++]))),
        10000 => array(array('id' => 7904, 'num' => intval($row[$i++]))),
    );
}

echo var_export($tempArr, true);
echo '<br />';
echo json_encode($tempArr);
?>
