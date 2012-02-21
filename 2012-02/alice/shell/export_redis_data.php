#!/usr/local/services/php-5.3.5/bin/php
<?php
if ($argc < 4) {
    echo <<<EOT
\033[35mUsage: ./export_redis_data.php <host> <port> <dbindex>
\033[0m
EOT;
    exit;
}
$host = $argv[1];
$port = $argv[2];
$dbindex = $argv[3];

$r = new Redis();
if ($r->connect($host, $port) === false) { // 连接失败
    exit("Can't connect to {$host}:{$port}!\n");
}
if ($r->select($dbindex) === false) { // 更换数据库失败
    exit("Can't select dbindex({$dbindex})!\n");
}
$keyArr = $r->getKeys('*');
$dataArr = array();
foreach ($keyArr as $key) {
    $type = $r->type($key);
    switch ($type) {
        case Redis::REDIS_STRING:
            $val = $r->get($key);
            break;
        case Redis::REDIS_SET:
            $val = $r->sMembers($key);
            break;
        case Redis::REDIS_LIST:
            $val = $r->lRange($key, 0, -1);
            break;
        case Redis::REDIS_HASH:
            $val = $r->hGetAll($key);
            break;
        case Redis::REDIS_ZSET:
            $val = $r->zRange($key, 0, -1, true);
            break;
        case Redis::REDIS_NOT_FOUND:
        default:
            exit("Unknown or unfinished type({$type})!\n");
            break;
    }
    if ($val === false) {
        exit("Can't get {$key}'s val!\n");
    }
    $dataArr[$type][$key] = $val;
}
$dataStr = '$redisDataArr = ' . var_export($dataArr, true) . ';';

unset($keyArr, $dataArr);

// 压缩一下
$dataStr = gzcompress($dataStr, 6);

$filename = "data_{$port}_{$dbindex}_" . md5($dataStr);
file_put_contents($filename, $dataStr);
echo "Export redis data to {$filename} OK!\n";
?>