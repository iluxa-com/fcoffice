<?php
/**
 * 递归扫描文件是否带UTF-8编码BOM
 * @param string $dir
 * @param string $pattern
 */
function scanUTF8BOM($dir, $pattern = NULL) {
    $baseDir = $dir;
    $dh = opendir($dir);
    if (!$dh) {
        exit("Can't open dir {$dir}!\n");
    }
    // 这里必须严格比较，因为返回的文件名可能是“0”
    while (($file = readdir($dh)) !== false) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        $filename = $baseDir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($filename)) {
            scanUTF8BOM($filename, $pattern);
        } else if ($pattern === NULL || preg_match($pattern, $file)) {
            $dataStr = file_get_contents($filename);
            if (substr($dataStr, 0, 3) === "\xEF\xBB\xBF") {
                echo "{$filename} has UTF-8 BOM\n";
            } else {
                //echo "{$filename} OK\n";
            }
        } else {
            // 跳过
        }
    }
    closedir($dh);
}

set_time_limit(0);
$dir = dirname(dirname(__FILE__));
scanUTF8BOM($dir, '/\.(php|html|htm|xml|js|css|sh)$/i');
?>