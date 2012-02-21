<?php
require_once 'config.inc.php';

$filename = 'task_data.csv';
$dataStr = file_get_contents($filename);
$dataStr = iconv('GBK', 'UTF-8', $dataStr);

$csv = new CSV();
$dataArr = $csv->restoreData($dataStr);

function getNpcId($name) {
    if ($name === '') {
        return 0;
    }
    $arr = array(
        '桑吉' => 1, '舒姗' => 2, '米娜' => 3, '道林' => 4, '雷姆' => 5,
        '多丽丝' => 6, '库鲁鲁' => 7, '小波特' => 8, '奇怪老人' => 9, '小精灵' => 10,
        '雷奥' => 11, '哈特' => 12, '黑衣人' => 13, '神秘人' => 14, '格蕾' => 15,
        '格特' => 16, '罗拉尔猎人' => 17, '族长' => 18, '智者' => 19, '芙蕾雅' => 20,
        '苏菲娅' => 21, '甲壳虫' => 22, '莱希大叔' => 23, '护卫队队长' => 24, '小莱希' => 25,
        '乞丐' => 26, '邦妮大婶' => 27, '拉伊' => 28,
    );
    if (isset($arr[$name])) {
        return $arr[$name];
    }
    exit("Unkown npc {$name}!");
}

$sqlArr = array();
foreach ($dataArr as $row) {
    $i = 0;
    $taskId = $row[$i++];
    if (!is_numeric($taskId)) { // 不是数值型,跳过
        continue;
    }
    $type = $row[$i++];
    $zoneId = $row[$i++];
    $placeId = $row[$i++];
    $areaId = $row[$i++];
    $grade = $row[$i++];
    $npcId = getNpcId($row[$i++]);
    $name = $row[$i++];
    $target = getNpcId($row[$i++]);
    $image = $row[$i++];
    // 任务需求
    $needItemIdArr = array();
    $needNumArr = array();
    $needItemIdArr[0] = $row[$i++];
    $needNumArr[0] = $row[$i++];
    $needItemIdArr[1] = $row[$i++];
    $needNumArr[1] = $row[$i++];
    $needItemIdArr[2] = $row[$i++];
    $needNumArr[2] = $row[$i++];
//    $needItemIdArr[3] = $row[$i++];
//    $needNumArr[3] = $row[$i++];
    $i++;
    $i++;
    // 任务奖励
    $rewardItemIdArr = array();
    $rewardNumArr = array();
    $rewardItemIdArr[0] = $row[$i++];
    $rewardNumArr[0] = $row[$i++];
    $rewardItemIdArr[1] = $row[$i++];
    $rewardNumArr[1] = $row[$i++];
    $rewardItemIdArr[2] = $row[$i++];
    $rewardNumArr[2] = $row[$i++];
    $rewardItemIdArr[3] = $row[$i++];
    $rewardNumArr[3] = $row[$i++];
    $rewardItemIdArr[4] = $row[$i++];
    $rewardNumArr[4] = $row[$i++];
    $dressItemArr = array();
    $dressItemId = getItemId($row[$i++]);
    if ($dressItemId !== '' && !is_numeric($dressItemId)) {
        echo "Unknown item {$dressItemId}\n";
    }
    $dressNum = $row[$i++];
    if (is_numeric($dressItemId) && is_numeric($dressNum)) {
        $dressItemArr['girl'] = array(
            'id' => $dressItemId,
            'num' => $dressNum,
        );
    }
    $dressItemId = getItemId($row[$i++]);
    if ($dressItemId !== '' && !is_numeric($dressItemId)) {
        echo "Unknown item {$dressItemId}\n";
    }
    $dressNum = $row[$i++];
    if (is_numeric($dressItemId) && is_numeric($dressNum)) {
        $dressItemArr['boy'] = array(
            'id' => $dressItemId,
            'num' => $dressNum,
        );
    }

    // 描述
    $description1 = $row[$i++];
    $description2 = $row[$i++];
    $description3 = $row[$i++];

    $npcTalk = $row[$i++];
    $manhua = $row[$i++];
    $levelId = $row[$i++];

    $needArr = array();
    for ($j = 0; $j < count($needItemIdArr); ++$j) {
        $itemId = getItemId($needItemIdArr[$j]);
        if ($itemId !== '' && !is_numeric($itemId)) {
            echo "Unknown item {$itemId}\n";
        }
        $num = $needNumArr[$j];
        if (!is_numeric($itemId) && !is_numeric($num)) {
            continue;
        }
        $needArr['items'][] = array(
            'id' => $itemId,
            'num' => intval($num),
        );
    }

    $rewardArr = array();
    for ($j = 0; $j < count($rewardItemIdArr); ++$j) {
        $itemId = getItemId($rewardItemIdArr[$j]);
        if ($itemId !== '' && !is_numeric($itemId)) {
            echo "Unknown item {$itemId}\n";
        }
        $num = $rewardNumArr[$j];
        if (!is_numeric($itemId) && !is_numeric($num)) {
            continue;
        }
        $rewardArr['items'][] = array(
            'id' => $itemId,
            'num' => intval($num),
        );
    }
    if (!empty($dressItemArr)) {
        $rewardArr['dress'] = $dressItemArr;
    }

    $needStr = json_encode($needArr);
    $rewardStr = json_encode($rewardArr);

    $sqlArr[] = "INSERT INTO task_data (`task_id`, `type`, `target`, `image`, `zone_id`, `place_id`, `area_id`, `grade`, `npc_id`, `name`, `need`, `reward`, `description1`, `description2`, `description3`, `npc_talk`, `manhua`, `level_id`) VALUES ('{$taskId}', '{$type}', '{$target}', '{$image}', '{$zoneId}', '{$placeId}', '{$areaId}', '{$grade}', '{$npcId}', '{$name}', '{$needStr}', '{$rewardStr}','{$description1}', '{$description2}', '{$description3}', '{$npcTalk}', '{$manhua}', '{$levelId}');";
}
$dataStr = implode("\r\n", $sqlArr);
echo $dataStr;
?>