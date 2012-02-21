#!/usr/local/services/php-5.3.5/bin/php
<?php
if ($argc < 2) {
    echo <<<EOT
\033[35mUsage: ./level_progress_crontab.php <platform>
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
for ($userId = $minUserId; $userId <= $maxUserId; ++$userId) {
    $userModel = new UserModel($userId);
    $progress = $userModel->hGet('progress', false);
    if ($progress === false) {
        continue;
    }
    list($areaId, $node, $flag) = explode('-', $progress);
    if (!isset($statArr[$areaId][$node])) {
        $statArr[$areaId][$node] = 1;
    } else {
        ++$statArr[$areaId][$node];
    }
}
$date = date('Y-m-d', strtotime('-1 day', CURRENT_TIME));
$dataArr = array(
    'date' => $date,
    'type' => 4, // 闯关进度
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
?>