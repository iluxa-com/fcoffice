<?php
/**
 * 验证表单所有字段
 * @param &array $dataArr 表单数据数组
 * @return int 所有字段验证通过，返回0，否则返回一个负数
 */
function checkAll(&$dataArr) {
    $figureurlArr = array(
        'http://res.fanhougame.com/alvv/images/head/1.jpg',
        'http://res.fanhougame.com/alvv/images/head/2.jpg',
        'http://res.fanhougame.com/alvv/images/head/3.jpg',
        'http://res.fanhougame.com/alvv/images/head/4.jpg',
        'http://res.fanhougame.com/alvv/images/head/5.jpg',
        'http://res.fanhougame.com/alvv/images/head/6.jpg',
        'http://res.fanhougame.com/alvv/images/head/7.jpg',
        'http://res.fanhougame.com/alvv/images/head/8.jpg',
        'http://res.fanhougame.com/alvv/images/head/9.jpg',
        'http://res.fanhougame.com/alvv/images/head/10.jpg',
        'http://res.fanhougame.com/alvv/images/head/11.jpg',
        'http://res.fanhougame.com/alvv/images/head/12.jpg',
        'http://res.fanhougame.com/alvv/images/head/13.jpg',
        'http://res.fanhougame.com/alvv/images/head/14.jpg',
        'http://res.fanhougame.com/alvv/images/head/15.jpg',
        'http://res.fanhougame.com/alvv/images/head/16.jpg',
        'http://res.fanhougame.com/alvv/images/head/17.jpg',
        'http://res.fanhougame.com/alvv/images/head/18.jpg',
        'http://res.fanhougame.com/alvv/images/head/19.jpg',
        'http://res.fanhougame.com/alvv/images/head/20.jpg',
    );
    shuffle($figureurlArr);
    $dataArr['figureurl'] = array_shift($figureurlArr);
    // 需要验证有效性的字段
    //$fieldArr = array('email', 'password', 'nickname', 'gender', 'province', 'city', 'figureurl', 'balance', 'is_vip', 'is_year_vip', 'vip_level');
    $fieldArr = array('email', 'password', 'nickname', 'gender', 'figureurl');
    // 遍历验证各字段
    foreach ($fieldArr as $field) {
        if (!isset($dataArr[$field])) {
            return -10;
        }
        $val = $dataArr[$field];
        switch ($field) {
            case 'email':
                if (!preg_match('/^[a-z0-9_\-\.]+@[a-z0-9_\-\.]+$/i', $val)) {
                    return -1;
                }
                break;
            case 'password':
                $dataArr['password'] = md5($dataArr['password']);
                break;
            case 'nickname':
            case 'gender':
            case 'province':
            case 'city':
            case 'figureurl':
            case 'balance':
            case 'is_vip':
            case 'is_year_vip':
                break;
            case 'vip_level':
                if (!$dataArr['is_vip']) {
                    $dataArr['vip_level'] = 0;
                }
                break;
        }
    }

    // 所有验证通过
    return 0;
}

if (isset($_GET['do']) && $_GET['do'] === 'reg' && !empty($_POST)) {
    // 引入配置文件
    require_once '../../config.php';

    if (App::get('Platform', false) !== 'Test') {
        exit('This platform is not supported!');
    }

    $dataArr = $_POST;
    $errorCode = checkAll($dataArr);
    if ($errorCode === 0) {
        $dataArr['is_vip'] = 0;
        $dataArr['is_year_vip'] = 0;
        $dataArr['vip_level'] = 0;
        $dataArr['balance'] = 0;
        $dataArr['create_time'] = time();
        if (SNS::createUser($dataArr)) {
            header('Location: login.php?email=' . $dataArr['email']);
            exit;
        } else {
            exit('Create sns user fail!');
        }
    } else {
        exit('请填写正确的信息(ErrorCode=' . $errorCode . ')!');
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Alice SNS Register</title>
        <style type="text/css">
            fieldset {
                width:500px;
                margin:0 auto;
                -moz-border-radius:5px;
                -webkit-border-radius:5px;
            }
            table {
                width:400px;
                margin:50px auto;
            }
            tr {
                height:32px;
                line-height:32px;
            }
            .td1 {
                width:100px;
                text-align:right;
                font-weight:bold;
            }
            .td2 {
                width:200px;
                text-align:center;
            }
            .td2 input {
                width:195px;
                font-weight:bold;
            }
            .td2 button {
                width:80px;
                line-height:20px;
            }
            .td3 {
                width:100px;
                text-align:left;
                text-indent:4px;
            }
        </style>
    </head>
    <body>
        <fieldset>
            <form name="form" id="form" action="register.php?do=reg" method="post">
                <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="td1">&nbsp;</td>
                        <td class="td2"><a href="login.php">已有账号，前去登录</a></td>
                        <td class="td3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="td1"><label for="email">E-mail：</label></td>
                        <td class="td2"><input type="text" id="email" name="email" /></td>
                        <td class="td3">*</td>
                    </tr>
                    <tr>
                        <td class="td1"><label for="password">密码：</label></td>
                        <td class="td2"><input type="password" id="password" name="password" maxlength="32" /></td>
                        <td class="td3">*</td>
                    </tr>
                    <tr>
                        <td class="td1"><label for="nickname">真实姓名：</label></td>
                        <td class="td2"><input type="text" id="nickname" name="nickname" /></td>
                        <td class="td3">*</td>
                    </tr>
                    <tr>
                        <td class="td1"><label for="gender">性别：</label></td>
                        <td class="td2" style="text-align:left;"><select id="gender" name="gender">
                                <option value="">－请选择－</option>
                                <option value="F">女</option>
                                <option value="M">男</option>
                            </select></td>
                        <td class="td3">*</td>
                    </tr>
                    <!--<tr>
                      <td class="td1"><label for="province">居住省份：</label></td>
                      <td class="td2"><input type="text" id="province" name="province" /></td>
                      <td class="td3">*</td>
                    </tr>
                    <tr>
                      <td class="td1"><label for="city">居住城市：</label></td>
                      <td class="td2"><input type="text" id="city" name="city" /></td>
                      <td class="td3">*</td>
                    </tr>
                    <tr>
                      <td class="td1"><label for="figureurl">头像链接：</label></td>
                      <td class="td2"><input type="text" id="figureurl" name="figureurl" /></td>
                      <td class="td3">*</td>
                    </tr>
                    <tr>
                      <td class="td1"><label for="balance">Q点数：</label></td>
                      <td class="td2"><input type="text" id="balance" name="balance" maxlength="8" value="0" /></td>
                      <td class="td3">*</td>
                    </tr>
                    <tr>
                      <td class="td1"><label for="is_vip">黄钻用户：</label></td>
                      <td class="td2" style="text-align:left;"><select id="is_vip" name="is_vip">
                          <option value="0">否</option>
                          <option value="1">是</option>
                        </select></td>
                      <td class="td3">*</td>
                    </tr>
                    <tr>
                      <td class="td1"><label for="is_year_vip">年费用户：</label></td>
                      <td class="td2" style="text-align:left;"><select id="is_year_vip" name="is_year_vip">
                          <option value="0">否</option>
                          <option value="1">是</option>
                        </select></td>
                      <td class="td3">*</td>
                    </tr>
                    <tr>
                      <td class="td1"><label for="vip_level">黄钻等级：</label></td>
                      <td class="td2" style="text-align:left;"><select id="vip_level" name="vip_level">
                          <option value="0">0级</option>
                          <option value="1">1级</option>
                          <option value="2">2级</option>
                          <option value="3">3级</option>
                          <option value="4">4级</option>
                          <option value="5">5级</option>
                          <option value="6">6级</option>
                          <option value="7">7级</option>
                        </select></td>
                      <td class="td3">*</td>
                    </tr>-->
                    <tr>
                        <td class="td1">&nbsp</td>
                        <td class="td2"><button type="submit">注册</button>&nbsp;<button type="reset">重置</button></td>
                        <td class="td3">&nbsp;</td>
                    </tr>
                </table>
            </form>
        </fieldset>
    </body>
</html>
