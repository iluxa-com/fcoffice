#!/usr/local/services/php-5.3.5/bin/php
<?php
if ($argc < 2) {
    echo <<<EOT
\033[35mUsage: ./update_2012_01_16.php <platform>
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
    $bagDataArr = array(
        7200 => 2, // 木棒
        7202 => 2, // 草绳
        7204 => 2, // 铁丝
        7205 => 2, // 蜂胶
    );
    foreach ($bagDataArr as $itemId => $num) {
        echo "{$userId}\t{$itemId}\t{$num}\t";
        var_dump(Bag::incrItem($itemId, $num, $userId));
    }
}
?>
