#!/usr/local/services/php-5.3.5/bin/php
<?php
if ($argc < 3) {
    echo <<<EOT
\033[35mUsage: ./rename_resource.php <src> <dst>
\033[0m
EOT;
    exit;
}

/**
 * 源路径(后面不要带"/")
 */
$srcDir = $argv[1];
/**
 * 目标路径(后面不要带"/")
 */
$dstDir = $argv[2];

/**
 * 计算签名
 * @param string $str 字符串
 * @return string
 */
function calcSign($str) {
    return base_convert(crc32($str), 10, 36);
}

/**
 * 重命名资源
 * @param string $srcDir 源路径
 * @param string $dstDir 目标路径
 */
function renameResource($srcDir, $dstDir) {
    if (!file_exists($dstDir)) {
        mkdir($dstDir, 0755, true);
    }
    $dh = opendir($srcDir);
    while (($file = readdir($dh)) !== false) {
        if ($file === '.' || $file === '..' || $file === '.svn') {
            continue;
        }
        $srcFilename = $srcDir . DIRECTORY_SEPARATOR . $file;
        $dstFilename = $dstDir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($srcFilename)) {
            renameResource($srcFilename, $dstFilename);
            continue;
        }
        if (preg_match('/\.fla$/', $file)) {
            continue;
        }
        if (preg_match('/^PreLoading-/', $file)) { // 跳过PreLoading-xxxxxxxx.swf
            copy($srcFilename, $dstFilename);
            continue;
        }
        $dotPos = strrpos($file, '.');
        if ($dotPos !== false) {
            $name = substr($file, 0, $dotPos);
            $ext = substr($file, $dotPos);
        } else {
            $name = $file;
            $ext = '';
        }
        $sign = calcSign(md5_file($srcFilename));
        $dstFilename = $dstDir . DIRECTORY_SEPARATOR . $name . '-' . $sign . $ext;
        if (!copy($srcFilename, $dstFilename)) {
            echo $srcFilename . " rename fail!\n";
        } else {
            if (isset($GLOBALS['FILE_SIGN'][$name])) {
                echo <<<EOT
========================================================================================
Another file has same name({$srcFilename})!
========================================================================================

EOT;
            }
            $GLOBALS['FILE_SIGN'][$name] = $sign;
        }
    }
}

if (!is_dir($srcDir)) {
    exit("'{$srcDir}' doesn't exists or isn't a directory!");
}
renameResource($srcDir, $dstDir);
if (!isset($GLOBALS['FILE_SIGN'])) {
    exit("No files found under directory($srcDir)!\n");
}
$dataStr = json_encode($GLOBALS['FILE_SIGN']);
$sign = calcSign(md5($dataStr));
file_put_contents($dstDir . DIRECTORY_SEPARATOR . 'config-' . $sign . '.json', $dataStr);
echo "Config file's sign is [{$sign}].\n";
?>
