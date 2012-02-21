#!/usr/local/services/php-5.3.5/bin/php
<?php
if ($argc < 5) {
    echo <<<EOT
\033[35mUsage: ./import_redis_data.php <host> <port> <dbindex> <filename>
\033[0m
EOT;
    exit;
}
$host = $argv[1];
$port = $argv[2];
$dbindex = $argv[3];
$filename = $argv[4];

if (!file_exists($filename)) { // 数据文件不存在
    exit("The file({$filename}) not exists!\n");
}

$dataStr = file_get_contents($filename);
// 解压
$dataStr = gzuncompress($dataStr);
eval($dataStr);
if (!isset($redisDataArr)) {
    exit("The \$redisDataArr undefined!\n");
}

unset($dataStr);

$r = new Redis();
if ($r->connect($host, $port) === false) { // 连接失败
    exit("Can't connect to {$host}:{$port}!\n");
}
if ($r->select($dbindex) === false) { // 更换数据库失败
    exit("Can't select dbindex({$dbindex})!\n");
}
if ($r->exists('__SYNC_FLAG__') === false) { // 同步标志Key不存在
    exit("Please set __SYNC_FLAG__ key!\n");
}
$keyArr = $r->getKeys('*');
if ($r->delete($keyArr) === false) { // 删除失败
    exit("Delete keys fail!\n");
}
foreach ($redisDataArr as $type => $dataArr) {
    switch ($type) {
        case Redis::REDIS_STRING:
            foreach ($dataArr as $key => $val) {
                if ($r->setnx($key, $val) === false) {
                    echo "Import [STRING] key({$key}) fail!\n";
                }
            }
            break;
        case Redis::REDIS_SET:
            foreach ($dataArr as $key => $val) {
                foreach ($val as $val2) {
                    if ($r->sAdd($key, $val2) === false) {
                        echo "Import [SET] key({$key}) fail!\n";
                    }
                }
            }
            break;
        case Redis::REDIS_LIST:
            foreach ($dataArr as $key => $val) {
                foreach ($val as $val2) {
                    if ($r->rPush($key, $val2) === false) {
                        echo "Import [LIST] key({$key}) fail!\n";
                    }
                }
            }
            break;
        case Redis::REDIS_HASH:
            foreach ($dataArr as $key => $val) {
                if ($r->hMset($key, $val) === false) {
                    echo "Import [HASH] key({$key}) fail!\n";
                }
            }
            break;
        case Redis::REDIS_ZSET:
            foreach ($dataArr as $key => $val) {
                foreach ($val as $key2 => $val2) {
                    if ($r->zAdd($key, $val2, $key2) === false) {
                        echo "Import [SORTED_SET] key({$key}) fail!\n";
                    }
                }
            }
            break;
        case Redis::REDIS_NOT_FOUND:
        default:
            exit("Unknown or unfinished type({$type})!\n");
            break;
    }
}
$r->setnx('__SYNC_INFO__', implode(' ', $argv) . ' @ ' . date('r'));
echo "Import {$filename} to redis OK!\n";
?>