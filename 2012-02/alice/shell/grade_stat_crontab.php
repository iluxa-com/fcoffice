#!/usr/local/services/php-5.3.5/bin/php
<?php
if ($argc < 2) {
    echo <<<EOT
\033[35mUsage: ./grade_crontab.php <platform>
\033[0m
EOT;
    exit;
}
$platform = strtolower($argv[1]);

require_once 'config.inc.php';

$relationModel = new RelationModel();
$minUserId = $relationModel->getMinUserId();
$maxUserId = $relationModel->getMaxUserId();
$statArr = array();
$statArr2 = array();
for ($i = 1; $i <= 100; ++$i) {
    $statArr[$i] = 0;
    $statArr2[$i] = 0;
}
for ($userId = $minUserId; $userId <= $maxUserId; ++$userId) {
    $userModel = new UserModel($userId);
    $exp = $userModel->hGet('exp', false);
    if ($exp === false) {
        $grade = 1;
    } else {
        $grade = Common::getGradeByExp($exp);
    }
    ++$statArr[$grade];
    $payFirst = $userModel->hGet('pay_first', false);
    if ($payFirst !== false) {
        ++$statArr2[$grade];
    }
}
$date = date('Y-m-d', strtotime('-1 day', CURRENT_TIME));

// 所有玩家
$dataArr = array(
    'date' => $date,
    'type' => 1, // 所有玩家等级分布统计
    'content' => json_encode($statArr),
);
$crontabDataSQLModel = new CrontabDataSQLModel();
try {
    $crontabDataSQLModel->SH()->insert($dataArr);
} catch (Exception $e) {
    echo CURRENT_TIME . "\n";
    echo var_export($dataArr, true);
    echo "\n\n";
}

// 付费玩家
$dataArr = array(
    'date' => $date,
    'type' => 6, // 付费玩家等级分布统计
    'content' => json_encode($statArr2),
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