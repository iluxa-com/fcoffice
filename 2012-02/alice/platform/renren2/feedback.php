<?php
if (time() >= 1312905600) {
    echo <<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>《童话迷城》－问卷调查</title>
<style type="text/css">
body {
	color:#111;
	font-size:14px;
	font-family:"宋体";
}
</style>
</head>
<body>
<div style="text-align:center;">本次调查活动已结束，近期将会统一给参与的玩家发放奖励，谢谢大家的支持！</div>
</body>
</html>
EOT;
    exit;
};
$questionArr = array(
    array('<童话迷城>解谜的创新玩法您喜欢吗？', '喜欢', '一般', '不喜欢'),
    array('<童话迷城>独树一帜的画面风格您喜欢吗？', '喜欢', '一般', '不喜欢'),
    array('新手引导能帮助您很快了解游戏吗？', '能', '勉强能', '不能'),
    array('游戏的任务设定您满意吗？', '满意', '一般', '不满意'),
    array('您觉得关卡难度是否合适？', '偏难', '合适', '偏简单'),
    array('游戏中您常去拜访好友吗？', '经常', '一般', '很少'),
    array('游戏的操作您觉得复杂吗？', '太复杂', '勉强很适应', '能很快上手'),
    array('总体来说，您在游戏中探险感觉顺畅吗？', '很顺畅', '基本顺畅', '很多地方不顺畅'),
    array('游戏里面最吸引您的地方有哪些呢？', '关卡解谜', '装扮服饰', '宠物', '精美画面'),
    array('游戏中的金币是否足够您探险使用？', '金币够用，不需要充值帮助', '金币基本够用，考虑消费来增强游戏性', '金币不够用，不充值无法继续游戏'),
    array('对目前的消费设置满意吗？', '很满意，愿意消费', '一般，考虑消费', '太单调，不愿消费'),
);
require_once '../../config.php';
if (isset($_GET['do']) && $_GET['do'] === 'feedback') {
    $dataArr = $_POST;
    $userId = isset($dataArr['user_id']) ? $dataArr['user_id'] : '';
    if (is_numeric($userId)) {
        $userModel = new UserModel($userId);
        if (!$userModel->exists()) {
            $msg = '对不起，你填写的ID不存在！';
        } else {
            $feedbackSQLModel = new FeedbackSQLModel();
            $whereArr = array('user_id' => $userId);
            $count = $feedbackSQLModel->SH()->find($whereArr)->count();
            if ($count < 1) {
                $feedbackSQLModel->SH()->insert($dataArr);
                $msg = '问卷调查提交成功，《童话迷城》感谢您的支持！';
            } else {
                $msg = '您的问卷调查已经提交，《童话迷城》感谢您的支持！';
            }
        }
    } else {
        $msg = '你填写的ID无效！';
    }
    echo <<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>《童话迷城》－问卷调查</title>
<style type="text/css">
body {
	color:#111;
	font-size:14px;
	font-family:"宋体";
}
</style>
</head>
<body>
<div style="text-align:center;">{$msg}</div>
</body>
</html>
EOT;
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>《童话迷城》－问卷调查</title>
        <script type="text/javascript" src="http://alrr.fanhougame.com/admin/js/jquery-1.5.min.js"></script>
        <style type="text/css">
            body {
                color:#111;
                font-size:14px;
                font-family:"宋体";
                width:760px;
                margin:0 auto;
                margin-top:6px;
            }
            form {
                width:100%;
                margin-bottom:50px;
            }
            table {
                width:100%;
                background:#ddd;
            }
            tr {
                height:28px;
                line-height:28px;
            }
            td {
                background:#fff;
                text-indent:6px;
            }
            .question {
                background:#f8f8f8;
            }
            .header {
                font-size:20px;
                font-weight:bold;
                text-align:center;
                border:1px solid #ddd;
                background:#f8f8f8;
                padding:10px 0;
            }
            .title {
                font-weight:bold;
                margin:15px 0 10px 0;
            }
            .textarea textarea {
                width:100%;
            }
            .btn {
                text-align:center;
            }
            .btn button {
                width:100px;
                height:30px;
                line-height:30px;
                margin:0 5px;
            }
            #top {
                background:#f8f8f8;
                color:#ffa153;
                padding:15px 0;
                margin-bottom:6px;
                font-size:13px;
                font-weight:bold;
                text-align:center;
            }
            .td1 {
                width:100px;
                text-align:right;
            }
            .td2 {
                width:240px;
            }
        </style>
        <script type="text/javascript">
            function checkAll() {
                var radioCount = <?php echo count($questionArr); ?>;
                if ($('input[type=radio]:checked').size() != radioCount) {
                    alert('还有部分项没选择，请选择！');
                    return false;
                }
                var obj = $('textarea[name=suggestion]');
                if (obj.val() == '') {
                    alert('请填写建议或意见！');
                    obj.focus();
                    return false;
                }
                obj = $('input[name=user_id]');
                if (obj.val() == '') {
                    alert('请填写您的游戏ID！');
                    obj.focus();
                    return false;
                }
                return true;
            }
        </script>
    </head>
    <body>
        <form id="frm" action="?do=feedback" method="post" onsubmit="return checkAll();">
            <div class="header">《童话迷城》－问卷调查　<a href="http://page.renren.com/699146821/group/334422658" target="_blank">活动详情</a></div>
            <div class="title">一、体验感题目</div>
            <table border="0" cellpadding="0" cellspacing="1">
                <?php
                foreach ($questionArr as $index => $arr) {
                    $id = $index + 1;
                    echo <<<EOT
<tr><td class="question">{$id}、{$arr[0]}</td></tr>
<tr><td class="answer">
EOT;
                    array_shift($arr);
                    foreach ($arr as $point => $val) {
                        echo <<<EOT
<div><input type="radio" id="p{$id}{$point}" name="p{$id}" value="{$point}" /><label for="p{$id}{$point}">{$val}</label></div>
EOT;
                    }
                    echo <<<EOT
</td>
</tr>
EOT;
                }
                ?>
            </table>
            <div class="title">二、您觉得<童话迷城>有哪些不足之处以及您对游戏的建议(<童话迷城>的成长需要您的金玉良言！)</div>
            <div class="textarea">
                <textarea cols="40" rows="8" name="suggestion"></textarea>
            </div>
            <div class="title">三、请留下您的用户ID（游戏底部可查看），以方便我们给您发放礼物</div>
            <table border="0" cellpadding="0" cellspacing="1" style="margin-bottom:50px;">
                <tr>
                    <td class="td1">您的用户ID：</td>
                    <td class="td2"><input type="test" name="user_id" /></td>
                    <td class="td3">如果您还没开通我们的游戏，请点击<a href="http://apps.renren.com/dreaming_adventures/?ref=feedback" target="_blank">这里</a>前去开通。</td>
                </tr>
            </table>
            <div class="btn">
                <button type="submit">提交</button>
                <button type="reset">重置</button>
            </div>
        </form>
    </body>
</html>
