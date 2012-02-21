#!/usr/local/services/php-5.3.5/bin/php
<?php
if ($argc < 2) {
    echo <<<EOT
\033[35mUsage: ./top_user_silver_crontab.php <platform>
\033[0m
EOT;
    exit;
}
$platform = strtolower($argv[1]);

require_once 'config.inc.php';

$relationModel = new RelationModel();
$minUserId = $relationModel->getMinUserId();
$maxUserId = $relationModel->getMaxUserId();
$dataArr = array(
    1 => 0,
    2 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
);
for ($userId = $minUserId; $userId <= $maxUserId; ++$userId) {
    $userModel = new UserModel($userId);
    $num = $userModel->hGet('silver', false);
    if ($num === false) {
        continue;
    }
    /**
     * 记录各阶段金币存量的玩家总数；（分500至2000金币、2001至4000金币、4001至6000金币、6001至10000金币、10001至30000金币，30001以上金币六个阶段进行统计）
     */
    if ($num <= 499) {
        // 不统计
    } else if ($num <= 2000) {
        ++$dataArr[1];
    } else if ($num <= 4000) {
        ++$dataArr[2];
    } else if ($num <= 6000) {
        ++$dataArr[3];
    } else if ($num <= 10000) {
        ++$dataArr[4];
    } else if ($num <= 30000) {
        ++$dataArr[5];
    } else {
        ++$dataArr[6];
    }
}
$date = date('Y-m-d', strtotime('-1 day', CURRENT_TIME));
$dataArr = array(
    'date' => $date,
    'type' => 5, // 金币存量统计
    'content' => json_encode($dataArr),
);
$crontabDataSQLModel = new CrontabDataSQLModel();
try {
    $crontabDataSQLModel->SH()->insert($dataArr);
} catch (Exception $e) {
    echo CURRENT_TIME . "\n";
    echo var_export($dataArr, true);
    echo "\n\n";
}
?>