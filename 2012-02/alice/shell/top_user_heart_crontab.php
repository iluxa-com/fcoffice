#!/usr/local/services/php-5.3.5/bin/php
<?php
if ($argc < 2) {
    echo <<<EOT
\033[35mUsage: ./top_user_heart_crontab.php <platform>
\033[0m
EOT;
    exit;
}
$platform = strtolower($argv[1]);

require_once 'config.inc.php';

$relationModel = new RelationModel();
$minUserId = $relationModel->getMinUserId();
$maxUserId = $relationModel->getMaxUserId();
$dataArr = array();
// 前多少名
$count = 100;
for ($userId = $minUserId; $userId <= $maxUserId; ++$userId) {
    $userModel = new UserModel($userId);
    $num = $userModel->hGet('heart', false);
    if ($num === false) {
        continue;
    }
    $dataArr[$userId] = $num;
    if (count($dataArr) > $count) {
        asort($dataArr, SORT_NUMERIC);
        foreach ($dataArr as $userId2 => $num2) { // 移除第一个
            unset($dataArr[$userId2]);
            break;
        }
    }
}
// 按值排序(降序)
arsort($dataArr, SORT_NUMERIC);

// 追加SNS信息
$tempArr = appendSNSInfo('heart', $dataArr, true);

$dataStr = json_encode($tempArr);
$topDataModel = new TopDataModel('USER_HEART');
if ($topDataModel->set($dataStr) === false) {
    echo CURRENT_TIME . "\n";
    echo "{$dataStr}\n";
    echo "\n\n";
}
?>