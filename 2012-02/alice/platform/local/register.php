<?php
/**
 * 验证表单所有字段
 * @param &array $dataArr 表单数据数组
 * @return int 所有字段验证通过，返回0，否则返回一个负数
 */
function checkAll(&$dataArr) {
    $figureurlArr = array(
        'http://py2.qlogo.cn/campus/b15287a5891ba4e185b6b0e22e4aad92274e8d2a0f7321604b6ae1dfc157f84e61a7c1dad099c473/60',
        'http://py2.qlogo.cn/campus/c265e4bd629300c530a53bc66af4685b2b62b853e947f2a8d6bea42b7aee4025faff831ff315af73/60',
        'http://py3.qlogo.cn/campus/5b31a220521f8d299d8ac2e2fd8e55e9b82a5e510340419d544b5b3b2482b666175bdbe2f539b70a/60',
        'http://py2.qlogo.cn/campus/bcb5d21ddb89a084c9b5546db98b2bc0b981b70c93b24bae0fd4b918655e02f2e50883895d6a436f/60',
        'http://py2.qlogo.cn/campus/1be89d3ddf5e816f102f4d03d68aa51ecb931f998647bbb5a1a01b453927fb43c686fc409c523707/60',
        'http://py2.qlogo.cn/campus/1be89d3ddf5e816fbdabe3f8fc7b0f4a36c98484453909c520fbff68a498c9d520171eb1c82a5ee9/60',
        'http://py2.qlogo.cn/campus/c265e4bd629300c57ec41cf43ca7d7c124228366553f6caecdefee0b58688b740b71e313c23357ad/60',
        'http://py.qlogo.cn/campus/c265e4bd629300c58b1ff8db41c00d00cb3e796fdb9fc1ac5996c1c87aafb3b05ed9b3de290fa5c8/60',
        'http://py.qlogo.cn/campus/1df479dbcc6a160aec24236bf0b895d7d161f4c7d4073d9263762eaf3bed2dc960fd30e543653b6a/60',
        'http://py2.qlogo.cn/campus/1df479dbcc6a160a8fd58d6cfa994f6186c7291603f9e6412608819e3d038e0afdded8a056aa3750/60',
        'http://py2.qlogo.cn/campus/765a12fb8085a4d2a208c2071e2f4e78876d626e70a44405dab267764ca65e6662521666e9bb9502/60',
        'http://py.qlogo.cn/campus/c265e4bd629300c5c25ddb0f45c14c7b90757ffb199a5e97d45952df6f7c8a95d47aaa37fcdcb7b5/60',
        'http://py1.qlogo.cn/campus/cd4599852b81e759ce5945d8139c1cba5ab8059777066a36d97b66dbec9e000b33a79a35fc80d8c8/60',
        'http://py1.qlogo.cn/campus/d272637dc1af902a2b2a453dd3199b5f150e8249ae6f8aac87960698290f5be1096973de4cf6089f/60',
        'http://py1.qlogo.cn/campus/169866d687e97a61f91fadafdc91b9c37954a1b0e20fb07da237b4a60da2645a1b8b2d68454c395b/60',
        'http://py.qlogo.cn/campus/c265e4bd629300c54bffd389cd7a2def3de623e17ab42eac9e73036bad2e2fced8c98b7829b7585f/60',
        'http://py.qlogo.cn/campus/98980cd3c4e8fa617ab98159bc088506da0f508d6725ccbe6011d915451333d4da4a777d44059622/60',
        'http://py.qlogo.cn/campus/55af5585bf442aa33d5832c94efc23943cacedb5ded50e5c5b5574d33c0ffe8a6b12863413dc842f/60',
        'http://py3.qlogo.cn/campus/cdeb3ffd4d4beb973f5e54af78cf07f2e6f876c9934c13471d6cb10a2f6969124efc996446a888d4/60',
        'http://py.qlogo.cn/campus/6252f451d0debb4ced83a554c7add74b606e3719be1aef7fc66421a3da1018c9ef0188f69b6519aa/60',
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

    if (App::get('Platform', false) !== 'Local') {
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
