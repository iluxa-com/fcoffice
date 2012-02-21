<?php
/**
 * 调试输出信息
 * @param string $str
 */
function debug($str) {
    $str .= ( php_sapi_name() === 'cli') ? "\n" : '<br />';
    echo $str;
}

/**
 * 对密码进行编码
 * @param string $password 密码
 * @param string $verifycode 验证码
 * @param bool $encrypt 密码是否加密
 * @return string
 */
function encodePassword($password, $verifycode, $encrypt=false) {
    if (!$encrypt) {
        $password = strtoupper(md5(md5(md5($password, true), true)));
    }
    $password = strtoupper(md5($password . strtoupper($verifycode)));
    return $password;
}

/**
 * 获取验证码回调函数
 * @param string $A
 * @param string $B
 */
function ptui_checkVC($A, $B) {
    global $verifycode;
    if ($A === '0') {
        $verifycode = $B;
        debug('获取验证码成功！');
    } else {
        exit("A={$A}");
    }
}

/**
 * 登录回调函数
 * @param string $C
 * @param string $A
 * @param string $B 跳转URL
 * @param string $G
 * @param string $F 提示信息
 */
function ptuiCB($C, $A, $B, $G, $F) {
    if ($C === '0') {
        debug($F);
    } else {
        exit($F);
    }
}

/**
 * 执行HTTP请求
 * @param array $optionsArr
 * @return array
 */
function doHttpRequest($optionsArr) {
    debug('URL=' . $optionsArr[CURLOPT_URL]);
    $ch = curl_init();
    curl_setopt_array($ch, $optionsArr);
    $dataStr = curl_exec($ch);
    $errorNo = curl_errno($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    debug('Code=' . $code);
    curl_close($ch);
    return array($errorNo, $dataStr);
}

$uin = 1668659852;
$appid = 15004601;
$u = $uin;
$p = '933B222ECC16706A9F10830AD4EC2115';
$encrypt = true;
$r = rand();
$aid = $appid;
$verifycode = '';

$cookieFilename = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cookie';
$optionsArr = array(
    CURLOPT_AUTOREFERER => true,
    CURLOPT_BINARYTRANSFER => true, // TRUE to return the raw output when CURLOPT_RETURNTRANSFER is used.
    CURLOPT_FOLLOWLOCATION => true, // TRUE to follow any "Location: " header that the server sends as part of the HTTP header (note this is recursive, PHP will follow as many "Location: " headers that it is sent, unless CURLOPT_MAXREDIRS is set).
    //CURLOPT_HEADER => true, // TRUE to include the header in the output.
    //CURLOPT_POST => true, // TRUE to do a regular HTTP POST. This POST is the normal application/x-www-form-urlencoded kind, most commonly used by HTML forms.
    CURLOPT_RETURNTRANSFER => true, // TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
    CURLOPT_CONNECTTIMEOUT => 10, // The number of seconds to wait whilst trying to connect. Use 0 to wait indefinitely.
    CURLOPT_MAXREDIRS => 10, // The maximum amount of HTTP redirections to follow. Use this option alongside CURLOPT_FOLLOWLOCATION.
    CURLOPT_TIMEOUT => 60, // The maximum number of seconds to allow CURL functions to execute.
    CURLOPT_COOKIEFILE => $cookieFilename, // The name of the file containing the cookie data. The cookie file can be in Netscape format, or just plain HTTP-style headers dumped into a file.
    CURLOPT_COOKIEJAR => $cookieFilename, // The name of a file to save all internal cookies to when the connection closes.
    CURLOPT_ENCODING => 'gzip,deflate', // The contents of the "Accept-Encoding: " header. This enables decoding of the response. Supported encodings are "identity", "deflate", and "gzip". If an empty string, "", is set, a header containing all supported encoding types is sent.
    //CURLOPT_POSTFIELDS => '', // The full data to post in a HTTP "POST" operation.
    CURLOPT_USERAGENT => 'CURL',
    CURLOPT_URL => '',
);


// 获取验证码
$optionsArr[CURLOPT_HTTPGET] = true;
$optionsArr[CURLOPT_URL] = "http://ptlogin2.qq.com/check?uin={$uin}&appid={$appid}&r={$r}";
$dataArr = doHttpRequest($optionsArr);
$dataArr[0] !== 0 && exit('获取验证码失败(errorCode=' . $dataArr[0] . ')！');
// ptui_checkVC('0','!NI9');
eval($dataArr[1]);

// 密码编码处理
$p = encodePassword($p, $verifycode, $encrypt);

// 登录
$optionsArr[CURLOPT_HTTPGET] = true;
$optionsArr[CURLOPT_URL] = "http://ptlogin2.qq.com/login?u={$u}&p={$p}&verifycode={$verifycode}&aid={$aid}&u1=http%3A%2F%2Fpengyou.qq.com%2Findex.php%3Fmod%3Dlogin2%26act%3Dqqlogin&h=1&ptredirect=1&ptlang=2052&from_ui=1&dumy=&fp=loginerroralert&mibao_css=";
$dataArr = doHttpRequest($optionsArr);
$dataArr[0] !== 0 && exit('登录失败(errorCode=' . $dataArr[0] . ')！');
// ptuiCB('0','0','http://pengyou.qq.com/index.php?mod=login2&act=qqlogin','1','登录成功！');
// ptuiCB('3','0','','0','您输入的密码有误，请重试。');
eval($dataArr[1]);

// 获取openid,openkey
$optionsArr[CURLOPT_HTTPGET] = true;
$optionsArr[CURLOPT_URL] = "http://r.qzone.qq.com/cgi-bin/qzapp/userapp_getopeninfo.cgi?uin={$uin}&r={$r}&g_tk=358179942";
$dataArr = doHttpRequest($optionsArr);
$dataArr[0] !== 0 && exit('获取openid,openkey失败(errorCode=' . $dataArr[0] . ')！');
/*
  document.domain = "qq.com";
  _Callback(
  {"ret":0,
  "openid":"0000000000000000000000000223E78A",
  "openkey":"21F495A1296F5D87E893EED23CF34D73E6E8153E7109EDB0"});
 */
if (!preg_match('/\{[^\}]+\}/', $dataArr[1], $m)) {
    exit('获取openid,openkey失败(返回数据格式不正确)！');
}
$jsonArr = json_decode($m[0], true);
$openid = $jsonArr['openid'];
$openkey = $jsonArr['openkey'];
debug('获取openid,openkey成功！');

// 获取家园首页
$optionsArr[CURLOPT_HTTPGET] = true;
$optionsArr[CURLOPT_URL] = "http://main.homeland.qzoneapp.com/index.php?openid={$openid}&openkey={$openkey}";
$dataArr = doHttpRequest($optionsArr);
$dataArr[0] !== 0 && exit('获取家园首页失败(errorCode=' . $dataArr[0] . ')！');
debug('获取家园首页成功！');
//echo $dataArr[1];

// 获取GameInitService->getInitData
$postArr = array(
    'service' => 'GameInitService',
    'action' => 'getInitData',
    'params' => '[]',
);
$optionsArr[CURLOPT_POST] = true;
$optionsArr[CURLOPT_URL] = "http://main.homeland.qzoneapp.com/gateway.php";
$optionsArr[CURLOPT_NOBODY] = true;
$optionsArr[CURLOPT_POSTFIELDS] = http_build_query($postArr);
$dataArr = doHttpRequest($optionsArr);
$dataArr[0] !== 0 && exit('服务GameInitService->getInitData请求失败(errorCode=' . $dataArr[0] . ')！');
debug('服务GameInitService->getInitData请求成功！');
debug('All Done!');
?>