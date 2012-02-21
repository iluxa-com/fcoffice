<?php
require_once 'config.inc.php';

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

$dataArr = array(
    'sns_uid' => '',
    'email' => '',
    'password' => md5('123456'),
    'nickname' => '',
    'gender' => 'M',
    'province' => '广东',
    'city' => '深圳',
    'figureurl' => '',
    'is_vip' => 0,
    'is_year_vip' => 0,
    'vip_level' => 0,
    'balance' => 0,
    'create_time' => CURRENT_TIME,
);
echo '<pre>';
for ($i = 1; $i <= 200; ++$i) {
    $email = sprintf('test%03d@fanhougame.com', $i);
    $snsUid = SNS::genUid($email);
    $dataArr['sns_uid'] = $snsUid;
    $dataArr['email'] = $email;
    shuffle($figureurlArr);
    $dataArr['figureurl'] = $figureurlArr[0];
    $dataArr['nickname'] = sprintf('测试帐号%03d', $i);
    var_dump(SNS::createUser($dataArr));
    var_dump(User::createUser($snsUid));
}
?>