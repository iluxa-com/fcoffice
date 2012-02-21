<?php
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'e10adc3949ba59abbe56e057f20f883e');

if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_USER'] !== ADMIN_USERNAME || md5($_SERVER['PHP_AUTH_PW']) !== ADMIN_PASSWORD) {
    header("WWW-Authenticate: Basic realm=\"Login\"");
    header("HTTP/1.1 401 Unauthorized");
    exit('Please login first!');
}
require_once dirname(__FILE__) . '/../config.php';

if (!in_array(App::get('Platform'), array('Devel', 'Local'))) {
    exit('This platform is not supported!');
}

/**
 * 将CSV数据还原成数据
 * @param string $filename 文件名
 * @return array
 */
function parseCSVFile($filename) {
    $dataStr = file_get_contents($filename);
    $dataStr = iconv('GBK', 'UTF-8', $dataStr);
    $csv = new CSV();
    return $csv->restoreData($dataStr);
}

/**
 * 下载文件
 * @param string $filename 文件名
 * @param string $dataStr 数据
 */
function download($filename, $dataStr) {
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Component: must-revalidate, post-check=0, pre-check=0");
    header('Content-type: application/octet-stream');
    header('Content-Transfer-Encoding: binary');
    header('Content-Disposition: attachment; filename=' . $filename);
    header('Content-Length: ' . strlen($dataStr));
    exit($dataStr);
}

/**
 * 根据地区名称获取地区ID
 * @param string $name 地区名称
 * @return int/string
 */
function getAreaId($name) {
    if ($name === '') {
        return '';
    }
    if (is_numeric($name)) {
        return $name;
    }
    $areaDataSQLModel = new AreaDataSQLModel();
    $row = $areaDataSQLModel->SH()->find(array('name' => $name))->fields('area_id')->getOne();
    if (empty($row)) {
        return $name;
    } else {
        return $row['area_id'];
    }
}

/**
 * 根据道具名称获取道具ID
 * @param string $name 道具名称
 * @return int/string
 */
function getItemId($name) {
    if ($name === '') {
        return '';
    }
    if (is_numeric($name)) {
        return $name;
    }
    $itemDataSQLModel = new ItemDataSQLModel();
    $row = $itemDataSQLModel->SH()->find(array('item_name' => $name))->fields('item_id')->getOne();
    if (empty($row)) {
        return $name;
    } else {
        return $row['item_id'];
    }
}
?>