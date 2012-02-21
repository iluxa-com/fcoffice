#!/usr/local/services/php-5.3.5/bin/php
<?php
if ($argc < 2) {
    echo <<<EOT
\033[35mUsage: ./online_crontab.php <platform>
\033[0m
EOT;
    exit;
}
$platform = strtolower($argv[1]);

$startTime = microtime(true);
require_once 'config.inc.php';

list($year, $month, $day, $hour) = explode('-', date('Y-m-d-H', CURRENT_TIME));
// 获取上个月的天数
$lastMonthDays = date('t', strtotime('-1 month', CURRENT_TIME));
// 统计配置
$statConfigArr = array(
    'hour' => array(
        'flag' => true, // 统计标志
        'cycle' => 60 * 60, // 统计周期
        'active' => 0, // 活跃用户数
        'active2' => 0, // 活跃用户(等级>=4)
        'date' => date('Y-m-d H:00:00', strtotime('-1 hour', CURRENT_TIME)), // 时间
        'model' => 'StatUserHourSQLModel', // 模型类名
    ),
    'day' => array(
        'flag' => ($hour === '00') ? true : false,
        'cycle' => 24 * 60 * 60,
        'active' => 0,
        'active2' => 0,
        'date' => date('Y-m-d', strtotime('-1 day', CURRENT_TIME)),
        'model' => 'StatUserDaySQLModel',
    ),
    'month' => array(
        'flag' => ($hour === '00' && $day === '01') ? true : false,
        'cycle' => $lastMonthDays * 24 * 60 * 60,
        'active' => 0,
        'active2' => 0, // 活跃用户(月登录次数>=2)
        'active3' => 0, // 活跃用户(月登录次数>=3 &&等级>=4)
        'date' => date('Y-m-01', strtotime('-1 month', CURRENT_TIME)),
        'model' => 'StatUserMonthSQLModel',
    ),
);
foreach ($statConfigArr as $type => $configArr) {
    if (!$configArr['flag']) {
        unset($statConfigArr[$type]);
    }
}

$relationModel = new RelationModel();
$minUserId = $relationModel->getMinUserId();
$maxUserId = $relationModel->getMaxUserId();
for ($userId = $minUserId; $userId <= $maxUserId; ++$userId) {
    $userModel = new UserModel($userId);
    $lastLogin = $userModel->hGet('last_login', false);
    if ($lastLogin === false) {
        continue;
    }
    if ($lastLogin == 0) {
        $lastLogin = $userModel->hGet('create_time', false);
    }
    if ($lastLogin === false) {
        continue;
    }
    foreach ($statConfigArr as $type => $configArr) {
        if ($lastLogin + $configArr['cycle'] > CURRENT_TIME) {
            ++$statConfigArr[$type]['active'];
            if ($type === 'day') {
                $grade = Common::getGradeByExp($userModel->hGet('exp', 0));
                if ($grade >= 4) {
                    ++$statConfigArr[$type]['active2'];
                }
            }
            if ($type === 'month') {
                $monthLoginTimes = $userModel->hGet('month_login_times', 0);
                if ($monthLoginTimes >= 2) {
                    ++$statConfigArr[$type]['active2'];
                    $grade = Common::getGradeByExp($userModel->hGet('exp', 0));
                    if ($monthLoginTimes >= 3 && $grade >= 4) {
                        ++$statConfigArr[$type]['active3'];
                    }
                }
            }
        }
    }
}


$totalUser = ($maxUserId - $minUserId) + 1;
foreach ($statConfigArr as $type => $configArr) {
    $dataArr = array(
        'date' => $configArr['date'],
        'total' => $totalUser,
        'active' => $configArr['active'],
    );
    if ($type === 'hour') {
        $statDataModel = new StatDataModel('USER_HOUR', $configArr['date']);
        $payIn = $statDataModel->hGet('pay_in', 0);
        $dataArr['pay_in'] = $payIn;
    }
    if ($type === 'day') {
        $statDataModel = new StatDataModel('DETAIL', $configArr['date']);
        $statDataArr = $statDataModel->hGetAll();
        if (!empty($statDataArr)) {
            $dataArr = array_merge($dataArr, $statDataArr);
        }
        $dataArr['active2'] = $configArr['active2'];
        // 充值统计
        $goldRechargeSQLModel = new GoldRechargeSQLModel('*');
        $serverGroup = $goldRechargeSQLModel->getServerGroup();
        $serverConfigArr = App::get('MySQLServer');
        $start = strtotime('yesterday', CURRENT_TIME);
        $end = $start + 86400;
        $payNum = 0;
        $payIn = 0;
        foreach ($serverConfigArr[$serverGroup] as $nodeId => $config) {
            $circleId = $nodeId - 1;
            $goldRechargeSQLModel = new GoldRechargeSQLModel($circleId);
            $sql = "select count(distinct(user_id)) as pay_num, sum(src_num) as pay_in from gold_recharge where type=0 and finished=1 and create_time>={$start} and create_time<{$end}";
            $row = $goldRechargeSQLModel->SH()->DH()->getOne($sql);
            if (!empty($row)) {
                $payNum += $row['pay_num'];
                $payIn += $row['pay_in'];
            }
        }
        $dataArr['pay_num'] = $payNum;
        $dataArr['pay_in'] = $payIn;
    }
    if ($type === 'month') {
        $dataArr['active2'] = $configArr['active2'];
        $dataArr['active3'] = $configArr['active3'];
    }
    $obj = App::getInst($configArr['model']);
    try {
        $obj->SH()->insert($dataArr);
    } catch (Exception $e) {
        echo CURRENT_TIME . "\n";
        echo $type . "\t" . json_encode($dataArr) . "\n";
        echo "\n\n";
    }
}
?>
