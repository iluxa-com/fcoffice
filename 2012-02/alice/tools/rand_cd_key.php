<?php
exit;
/**
 * 向指定文件追加内容
 * @param string $filename 文件名
 * @param string $str 要追加的内容
 */
function fileAppend($filename, $str) {
    $fp = fopen($filename, 'ab');
    if ($fp) {
        if (flock($fp, LOCK_EX)) {
            fwrite($fp, $str);
            flock($fp, LOCK_UN);
        }
        fclose($fp);
    }
}

/**
 * 生成一个指定长度的随机Key
 * @param int $len Key的长度
 * @return string
 */
function genRandKey($len) {
    $keyStr = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; // 有效的字符
    $maxIndex = strlen($keyStr) - 1;
    $key = '';
    while ($len-- > 0) {
        $key .= $keyStr[rand(0, $maxIndex)];
    }
    return $key;
}

/**
 * 生成随机CD-Key
 * @param string $prefix 前缀
 * @param int $num 编号
 * @return string
 */
function genCDKey($prefix, $num) {
    return sprintf('%08X', crc32($prefix . $num)) . genRandKey(8);
}

$filename = 'cd_key.txt';  // 保存文件名
$prefix = 'alice';      // 被计算CRC32值的字符串的前缀
$start = 1;             // 开始编号
$end = 100000;          // 结束编号

$str = '';
set_time_limit(0);
for ($i = $start; $i <= $end; ++$i) {
    $str .= genCDKey($prefix, $i) . "\n";
    if ($i % 50000 === 0) { // 每50000存储一次
        fileAppend($filename, $str);
        $str = '';
    }
}
if ($str !== '') {
    fileAppend($filename, $str);
}
?>