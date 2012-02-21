#!/usr/local/services/php-5.3.5/bin/php
<?php
if ($argc < 2) {
    echo <<<EOT
\033[35mUsage: ./user_all.php <platform>
\033[0m
EOT;
    exit;
}
$platform = strtolower($argv[1]);

require_once 'config.inc.php';

$relationModel = new RelationModel();
$maxUserId = $relationModel->getMaxUserId();
for ($userId = 1; $userId <= $maxUserId; ++$userId) {
    $userModel = new UserModel($userId);
    if (!$userModel->exists()) { // 不存在,跳过
        continue;
    }
}
?>