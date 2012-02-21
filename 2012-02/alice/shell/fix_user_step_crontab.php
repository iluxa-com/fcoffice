#!/usr/local/services/php-5.3.5/bin/php
<?php
if ($argc < 3) {
    echo <<<EOT
\033[35mUsage: ./fix_user_step_crontab.php <platform> <start>
\033[0m
EOT;
    exit;
}
$platform = strtolower($argv[1]);
$startTime = $argv[2];

require_once 'config.inc.php';

$type = 15;
$endTime = strtotime('yesterday', CURRENT_TIME);

for ($i = $startTime; $i <= $endTime; $i += 86400) {
    $start = $i;
    $end = $start + 86400;
    $subKey = date('Y-m-d', $start);

    $sql = "select val1 as step,count(distinct(user_id)) as total from action_record where type={$type} and time>={$start} and time<{$end} group by val1";

    $actionRecordSQLModel = new ActionRecordSQLModel();
    $rowArr = $actionRecordSQLModel->SH()->DH()->getAll($sql);
    $dataArr = array();
    foreach ($rowArr as $row) {
        $dataArr['s' . $row['step']] = $row['total'];
    }
    try {
        $stepDataSQLModel = new StepDataSQLModel();
        $whereArr = array(
            'date' => $subKey,
        );
        $stepDataSQLModel->SH()->find($whereArr)->update($dataArr);
    } catch (Exception $e) {
        echo CURRENT_TIME . "\n";
        echo var_export($dataArr, true);
        echo "\n\n";
    }
}
?>