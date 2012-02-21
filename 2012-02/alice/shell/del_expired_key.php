#!/usr/local/services/php-5.3.5/bin/php
<?php
if ($argc < 2) {
    echo <<<EOT
\033[35mUsage: ./del_expired_key.php <platform>
\033[0m
EOT;
    exit;
}
$platform = strtolower($argv[1]);

require_once 'config.inc.php';

$relationModel = new RelationModel();
$maxUserId = $relationModel->getMaxUserId();
$today = strtotime('today', CURRENT_TIME);
$delKeyNum = 0;
for ($userId = 1; $userId <= $maxUserId; ++$userId) {
    $userModel = new UserModel($userId);
    if (!$userModel->exists()) { // 不存在,跳过
        continue;
    }
    $lastLogin = $userModel->hGet('last_login');
    if ($lastLogin >= $today) { // 今天有登录,跳过
        continue;
    }
    $num = User::delExpiredKey($userId);
    if ($num !== false) { // 累加计数
        $delKeyNum += $num;
    }
}
echo "delKeyNum={$delKeyNum}\n";
?>