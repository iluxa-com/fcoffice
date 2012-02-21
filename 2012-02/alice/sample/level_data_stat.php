<?php
$r = new Redis();
$r->connect('127.0.0.1', 7000);

$keyArr = $r->getKeys('__LS:*');
$levelSetArr = array();
foreach ($keyArr as $key) {
    $levelSetArr[$key] = $r->sMembers($key);
}

$levelDataArr = $r->hGetAll('__level_data');

$tempArr = array();
foreach ($levelDataArr as $levelId => $jsonStr) {
    $k = NULL;
    foreach ($levelSetArr as $key => $levelSet) {
        if (in_array($levelId, $levelSet)) {
            $k = $key;
            break;
        }
    }
    if ($k === NULL) {
        continue;
    }
    $arr = explode(':', $k);
    $jsonArr = json_decode($jsonStr, true);
    if ($jsonArr === NULL) {
        $tempArr[$levelId] = array(
            'area_id' => $arr[1],
            'skill_level(node)' => $arr[2],
            'difficulty(mode)' => $arr[3],
            'level_id' => $levelId,
            'lv1' => 0,
            'lv2' => 0,
            'lv3' => 0,
            'lv4' => 0,
            'lv5' => 0,
            'lv6' => 0,
            'lv7' => 0,
            'lv8' => 0,
            'lv9' => 0,
            'lv10' => 0,
            'lv11' => 0,
            'lv12' => 0,
            'lv13' => 0,
            'length' => strlen($jsonStr),
        );
    } else {
        $tempArr[$levelId] = array(
            'area_id' => $arr[1],
            'skill_level(node)' => $arr[2],
            'difficulty(mode)' => $arr[3],
            'level_id' => $levelId,
            'lv1' => isset($jsonArr['chest']['lv1']) ? count($jsonArr['chest']['lv1']) : 0,
            'lv2' => isset($jsonArr['chest']['lv2']) ? count($jsonArr['chest']['lv2']) : 0,
            'lv3' => isset($jsonArr['chest']['lv3']) ? count($jsonArr['chest']['lv3']) : 0,
            'lv4' => isset($jsonArr['chest']['lv4']) ? count($jsonArr['chest']['lv4']) : 0,
            'lv5' => isset($jsonArr['chest']['lv5']) ? count($jsonArr['chest']['lv5']) : 0,
            'lv6' => isset($jsonArr['chest']['lv6']) ? count($jsonArr['chest']['lv6']) : 0,
            'lv7' => isset($jsonArr['chest']['lv7']) ? count($jsonArr['chest']['lv7']) : 0,
            'lv8' => isset($jsonArr['chest']['lv8']) ? count($jsonArr['chest']['lv8']) : 0,
            'lv9' => isset($jsonArr['chest']['lv9']) ? count($jsonArr['chest']['lv9']) : 0,
            'lv10' => isset($jsonArr['chest']['lv10']) ? count($jsonArr['chest']['lv10']) : 0,
            'lv11' => isset($jsonArr['chest']['lv11']) ? count($jsonArr['chest']['lv11']) : 0,
            'lv12' => isset($jsonArr['chest']['lv12']) ? count($jsonArr['chest']['lv12']) : 0,
            'lv13' => isset($jsonArr['chest']['lv13']) ? count($jsonArr['chest']['lv13']) : 0,
            'length' => strlen($jsonStr),
        );
    }
}

/**
 * 下载文件
 * @param string $filename 文件名
 * @param string $dataStr 数据
 */
function download($filename, $dataStr) {
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Component: must-revalidate, post-check=0, pre-check=0");
    header('Content-type: application/octet-stream');
    header('Content-Transfer-Encoding: binary');
    header('Content-Disposition: attachment; filename=' . $filename);
    header('Content-Length: ' . strlen($dataStr));
    exit($dataStr);
}

require_once 'CSV.php';

$csv = new CSV();
$dataStr = $csv->makeData($tempArr);
download('level_data_stat.csv', $dataStr);
?>
