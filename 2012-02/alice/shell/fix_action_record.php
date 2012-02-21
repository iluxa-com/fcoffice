#!/usr/local/services/php-5.3.5/bin/php
<?php
if ($argc < 2) {
    echo <<<EOT
\033[35mUsage: ./fix_action_record.php <platform>
\033[0m
EOT;
    exit;
}
$platform = strtolower($argv[1]);

require_once 'config.inc.php';

$relationModel = new RelationModel();
$maxUserId = $relationModel->getMaxUserId();
$tempArr = array();
for ($userId = 1; $userId <= $maxUserId; ++$userId) {
    $userModel = new UserModel($userId);
    if (!$userModel->exists()) { // 不存在,跳过
        continue;
    }
    $createTime = $userModel->hGet('create_time', false);
    if ($createTime === false) {
        continue;
    }
    $start = strtotime('today', $createTime);
    $tempArr[$start][] = $userId;
}

$actionRecordSQLModel = new ActionRecordSQLModel();
foreach ($tempArr as $start => $userIdArr) {
    $whereArr = array(
        '>=' => array(
            'create_time' => $start,
        ),
        '<' => array(
            'create_time' => $start + 86400,
        ),
    );
    $end = $start + 86400;
    $userIdStr = implode(',', $userIdArr);
    $sql = "delete from action_record where type=15 and time>={$start} and time<{$end} and user_id not in ({$userIdStr})";
    $result = $actionRecordSQLModel->SH()->DH()->query($sql);
    echo date('Y-m-d H:i:s', $start) . "\n";
    var_dump($result);
}
$keyArr = array_keys($tempArr);
asort($keyArr, SORT_NUMERIC);
print_r($keyArr);
?>