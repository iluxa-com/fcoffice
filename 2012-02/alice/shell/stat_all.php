#!/usr/local/services/php-5.3.5/bin/php
<?php
if ($argc < 2) {
    echo <<<EOT
\033[35mUsage: ./stat_all.php <platform>
\033[0m
EOT;
    exit;
}
$platform = strtolower($argv[1]);

require_once 'config.inc.php';

$relationModel = new RelationModel();
$maxUserId = $relationModel->getMaxUserId();
$tempArr = array();
$start = 1320800400;
$end = $start + 86400;
for ($userId = 1; $userId <= $maxUserId; ++$userId) {
    $userModel = new UserModel($userId);
    if (!$userModel->exists()) { // 不存在,跳过
        continue;
    }
    $arr = $userModel->hMget(array('user_id', 'gender', 'exp', 'progress', 'level_success', 'level_fail', 'create_time', 'last_login'));
    //if ($arr['create_time'] >= $start && $arr['create_time'] < $end) {
        $arr['progress'] = str_replace('-', '=', $arr['progress']); // Excel会把x-y-z这样的字符串当成日期,变通一下
        $arr['create_time'] = date('Y-m-d H:i:s', $arr['create_time']);
        $arr['last_login'] = date('Y-m-d H:i:s', $arr['last_login']);
        $tempArr[] = $arr;
    //}
}

$csv = new CSV();
echo $csv->makeData($tempArr);
?>