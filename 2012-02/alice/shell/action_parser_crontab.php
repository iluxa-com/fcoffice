#!/usr/local/services/php-5.3.5/bin/php
<?php
if ($argc < 2) {
    echo <<<EOT
\033[35mUsage: ./action_record_parser.php <platform>
\033[0m
EOT;
    exit;
}
$platform = strtolower($argv[1]);

require_once 'config.inc.php';

/**
 * 解析数据
 * @param string $jsonStr
 * @return NULL/string
 */
function parseData($jsonStr) {
    $jsonArr = json_decode($jsonStr, true);
    if ($jsonArr === NULL) {
        return false;
    }
    $userId = $jsonArr['user_id'];
    $type = $jsonArr['type'];
    $val1 = isset($jsonArr['val1']) ? $jsonArr['val1'] : '';
    $val2 = isset($jsonArr['val2']) ? $jsonArr['val2'] : '';
    $val3 = isset($jsonArr['val3']) ? $jsonArr['val3'] : '';
    $time = $jsonArr['time'];
    return "('{$userId}','{$type}','{$val1}','{$val2}','{$val3}','{$time}')";
}

$sqlPrefix = "INSERT INTO `action_record` (`user_id`, `type`, `val1`, `val2`, `val3`, `time`) VALUES\n";
if ($platform === 'pengyou') {
    $actionModel = new ActionModel('*');
    $keyArr = $actionModel->RH()->getKeys($actionModel->getStoreKey());
    $actionRecordSQLModel = new ActionRecordSQLModel();
    foreach ($keyArr as $key) {
        $arr = explode(':', $key);
        $time = $arr[1] * 1800;
        if (CURRENT_TIME - $time < 3600) {
            continue;
        }
        $dataStr = $actionModel->RH()->get($key);
        $dataArr = explode("\n", $dataStr);
        $tempArr = array();
        foreach ($dataArr as $jsonStr) {
            $ret = parseData($jsonStr);
            if ($ret !== false) {
                $tempArr[] = $ret;
            }
        }
        $sql = $sqlPrefix . implode(",\n", $tempArr) . ';';
        try {
            $actionRecordSQLModel->SH()->DH()->query($sql);
            $actionModel->RH()->delete($key);
        } catch (Exception $e) {
            echo $e->getTraceAsString();
        }
        unset($tempArr, $sql);
    }
    exit;
}
$actionRecordSQLModel = new ActionRecordSQLModel();
foreach (glob("/tmp/{$platform}_log/*.log") as $filename) {
    if (filemtime($filename) >= strtotime('-1 hour', CURRENT_TIME)) { // 一小时之内的跳过
        continue;
    }
    $tempArr = array();
    $fp = fopen($filename, 'rb');
    while (!feof($fp)) {
        $jsonStr = rtrim(fgets($fp)); // 移除末尾的换行符
        $ret = parseData($jsonStr);
        if ($ret !== false) {
            $tempArr[] = $ret;
        }
    }
    fclose($fp);
    $sql = $sqlPrefix . implode(",\n", $tempArr) . ';';
    try {
        $actionRecordSQLModel->SH()->DH()->query($sql);
    } catch (Exception $e) {
        echo $e->getTraceAsString();
    }
    unset($tempArr, $sql);
    unlink($filename);
}
?>