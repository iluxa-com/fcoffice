<?php
require_once 'config.inc.php';

$relationModel = new RelationModel();
$maxUserId = $relationModel->getMaxUserId();
echo '<pre>';
for ($userId = 1; $userId <= $maxUserId; ++$userId) {
    $userModel = new UserModel($userId);
    if (!$userModel->exists()) { // 不存在,跳过
        continue;
    }
}
?>