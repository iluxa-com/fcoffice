#!/usr/local/services/php-5.3.5/bin/php
<?php
if ($argc < 2) {
    echo <<<EOT
\033[35mUsage: ./user_step_crontab.php <platform>
\033[0m
EOT;
    exit;
}
$platform = strtolower($argv[1]);

require_once 'config.inc.php';

$type = 15;
$start = strtotime('yesterday', CURRENT_TIME);
$end = $start + 86400;
$subKey = date('Y-m-d', $start);

$sql = "select val1 as step,count(distinct(user_id)) as total from action_record where type={$type} and time>={$start} and time<{$end} group by val1";

$actionRecordSQLModel = new ActionRecordSQLModel();
$rowArr = $actionRecordSQLModel->SH()->DH()->getAll($sql);
$dataArr = array();
foreach ($rowArr as $row) {
    $dataArr['s' . $row['step']] = $row['total'];
}
$dataArr['date'] = $subKey;
try {
    $stepDataSQLModel = new StepDataSQLModel();
    $stepDataSQLModel->SH()->insert($dataArr);
} catch (Exception $e) {
    echo CURRENT_TIME . "\n";
    echo var_export($dataArr, true);
    echo "\n\n";
}
?>