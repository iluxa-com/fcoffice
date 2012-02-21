<?php
// 移动关卡ID
require_once 'config.inc.php';

$filename = 'level_id_move.csv';
$dataStr = file_get_contents($filename);
$dataStr = iconv('GBK', 'UTF-8', $dataStr);

$csv = new CSV();
$dataArr = $csv->restoreData($dataStr);

/**
 * 获取难度
 * @param string $name
 * @return int
 */
function getDifficulty($name) {
    if ($name === '简单') {
        return 0;
    } else if ($name === '普通') {
        return 1;
    } else if ($name === '困难') {
        return 2;
    } else {
        exit("Unknown difficulty {$name}!");
    }
}

$tempArr = array();
foreach ($dataArr as $row) {
    $i = 0;
    $srcAreaId = getAreaId($row[$i++]);
    if (!is_numeric($srcAreaId)) {
        continue;
    }
    $srcDifficulty = getDifficulty($row[$i++]);
    $levelIdStr = $row[$i++];
    $levelIdArr = explode('.', $levelIdStr);
    $dstAreaId = getAreaId($row[$i++]);
    if (!is_numeric($dstAreaId)) {
        continue;
    }
    $dstDifficulty = getDifficulty($row[$i++]);
    $tempArr[] = array(
        'src_area_id' => $srcAreaId,
        'src_difficulty' => $srcDifficulty,
        'level_ids' => $levelIdArr,
        'dst_area_id' => $dstAreaId,
        'dst_difficulty' => $dstDifficulty,
    );
}

foreach ($tempArr as $row) {
    $srcAreaId = $row['src_area_id'];
    $srcDifficulty = $row['src_difficulty'];
    $dstAreaId = $row['dst_area_id'];
    $dstDifficulty = $row['dst_difficulty'];
    $srcLevelSetModel = new LevelSetModel($srcAreaId, 0, $srcDifficulty);
    $dstLevelSetModel = new LevelSetModel($dstAreaId, 0, $dstDifficulty);
    foreach ($row['level_ids'] as $levelId) {
        echo $levelId . " " . $srcLevelSetModel->getStoreKey() . " => " . $dstLevelSetModel->getStoreKey() . "<br />";
        if ($srcLevelSetModel->RH()->sMove($srcLevelSetModel->getStoreKey(), $dstLevelSetModel->getStoreKey(), $levelId) === false) {
            echo 'move fail<br />';
        }
    }
}
?>