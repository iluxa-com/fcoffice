<?php
// 上传文件保存路径
$uploadDir = '/data/www/alicedev/upload';

// POST表单字段名
$field = 'Filedata';

if (!empty($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
    $filename = basename($_FILES[$field]['name']);
    if (!preg_match('/\.swf$/i', $filename)) { // 扩展名检查
        exit('bad file type!');
    }
    if (!isset($_GET['path']) || !preg_match('/^[a-z0-9\_]+$/i', $_GET['path'])) { // 子路径有效性检查
        exit('bad sub path!');
    }
    $subDir = $_GET['path'];
    $src = $_FILES[$field]['tmp_name'];
    $dir = $uploadDir . DIRECTORY_SEPARATOR . $subDir;
    if (!file_exists($dir)) { // 如果文件夹不存在,自动建创
        mkdir($dir, 0775, true);
    }
    $dst = $dir . DIRECTORY_SEPARATOR . $filename;
    if (move_uploaded_file($src, $dst)) {
        exit('upload ok!');
    } else {
        exit('upload error!');
    }
} else {
    exit('no upload file!');
}
?>