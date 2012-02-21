#!/usr/local/services/php-5.3.5/bin/php
<?php
if ($argc < 2) {
    echo <<<EOT
\033[35mUsage: ./init_default_user.php <platform>
\033[0m
EOT;
    exit;
}
$platform = strtolower($argv[1]);

require_once 'config.inc.php';

// 取默认好友
$defaultFriendInfoArr = App::get('DefaultFriend', false);
if ($defaultFriendInfoArr === false) {
    exit("No default friend!\n");
}
$userId = $defaultFriendInfoArr['user_id'];
$snsUid = $defaultFriendInfoArr['sns_uid'];
$relationModel = new RelationModel($snsUid);
$userId2 = $relationModel->get();
if (is_numeric($userId2)) {
    exit("Already init!\n");
}
if ($userId2 === false) { // 没取到
    if ($relationModel->setnx($userId . '=INIT') === false) { // 设置关系失败
        exit("Error while set relation!\n");
    }
}
if (User::createUser($snsUid) === false) { // 创建角色失败
    exit("Error while create user!\n");
}
// 设置经验
$exp = Common::getExpByGrade(100);
$userModel = new UserModel($userId);
if ($userModel->hSet('exp', $exp) === false) {
    exit("Error while set exp!\n");
}
// 第一个任务槽发布一个分享任务
$taskSlotModel = new TaskSlotModel($userId);
$dataArr = array(
    'task_id' => 500,
    'time' => CURRENT_TIME,
    'user_id' => 0,
    'need' => array('items' => array(array('id' => 7001, 'num' => 1))),
    'reward' => array('items' => Common::randSlotTaskReward(1)),
    'grade' => 1,
);
$jsonStr = json_encode($dataArr);
if ($taskSlotModel->hSet(1, $jsonStr) === false) { // 发布失败
    exit("Error while pub slot task!\n");
}
// 设置家装饰
$homeModel = new HomeModel($userId);
$homeDataArr = array(
    'background' => 2080, // 背景
    'house' => '{"id":2094,"level":0}', // 房子
    'board' => '{"id":2090,"level":0}', // 告示板
    'postbox' => '{"id":2082,"level":0}', // 邮箱
    'tree' => '{"id":2084,"level":0,"last_pick":0}', // 魔法果树
    'horse' => '{"id":"2086","level":0,"last_level_up":0}', // 马车
    'honeybee' => '{"id":2085,"level":0,"last_pick":0,"last_level_up":0}', // 魔法蜂巢
    'workshop' => '{"id":2088,"level":0,"last_level_up":0}', // 精灵工坊
    'farm' => '{"id":2083,"level":0}', // 磨坊农田
    'trade' => '{"id":2092,"level":0}', // 摊位
    'spirit' => '{"id":2089,"level":0,"last_level_up":0}', // 精灵屋
    'totem' => '{"id":2091,"level":0}', // 图腾柱
    'radio' => '{"id":2093,"level":0}', // 收音机
);
if ($homeModel->hMset($homeDataArr) === false) {
    exit("Error while set home data\n");
}
echo "Init default friend OK!\n";
?>