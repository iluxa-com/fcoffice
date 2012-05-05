<?php
$name = 'falcon';
$password = '7340985';

/* 检查变量 $$_SERVER['PHP_AUTH_USER'] 和$$_SERVER['PHP_AUTH_PW'] 的值*/

if ((!isset($_SERVER['PHP_AUTH_USER'])) || (!isset($_SERVER['PHP_AUTH_PW']))) {

/* 空值：发送产生显示文本框的数据头部*/

header('WWW-Authenticate: Basic realm="Input tokens"');

header('HTTP/1.0 401 Unauthorized');

echo 'Authorization Required.';

exit;

} else if ((isset($_SERVER['PHP_AUTH_USER'])) && (isset($_SERVER['PHP_AUTH_PW']))){



    if (($_SERVER['PHP_AUTH_USER'] != $name) || ($_SERVER['PHP_AUTH_PW'] != $password)) {

    /* 用户名输入错误或密码输入错误，发送产生显示文本框的数据头部*/

    header('WWW-Authenticate: Basic realm="My Private Stuff"');

    header('HTTP/1.0 401 Unauthorized');

    echo 'Authorization Required.';

    exit;

    } else if (($_SERVER['PHP_AUTH_USER'] == $name) && ($_SERVER['PHP_AUTH_PW'] == $password)) {

    /* 用户名及密码都正确，输出成功信息 */

    //application/octet-stream 和 application/x-zip-compressed
    header('Content-type:'.'application/octet-stream');
    header('Content-Disposition: attachment; filename="ssh.zip"');

    // The PDF source is in original.pdf
    readfile('myfile/ssh.zip');


}

}

?> 