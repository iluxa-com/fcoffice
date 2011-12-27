<?php
@date_timezone_set('Asia/Shanghai');
define('AM',1);
define('PM',2);
define('TYPE', date('H')<12 ? AM : PM);
require_once('config.php');
require_once('httpconnector.php');
require_once('PHPFetion.php');
//规定时间之前 120 分钟到之后 0 分钟这段时间可进行上班登记，规定时间之前 0 分钟到之后 360 分钟这段时间可进行下班登记
$current_time = time();
if(TYPE === AM && ($current_time<strtotime('9:30')-120*60 OR $current_time>strtotime('9:30'))) {
	$msg_arr = array('result'=>false,'msg'=>'非上班打卡时间');
}elseif(TYPE === PM && ($current_time<strtotime('18:30') OR $current_time>strtotime('18:30')+360*60)) {
	$msg_arr = array('result'=>false,'msg'=>'非下班班打卡时间');
}else {
	$conn = new httpconnector();
	$params_str = "UNAME={$uname}&PASSWORD={$password}&UI=0&submit=%B5%C7+%C2%BC";
	$data = $conn->post('http://oa.fanhougame.com/logincheck.php',$params_str,false);
	$data2 = $conn->get('http://oa.fanhougame.com/general/attendance/personal/duty/submit.php?REGISTER_TYPE='.TYPE); //早上1,晚上2
	$data3 = $conn->get('http://oa.fanhougame.com/general/attendance/personal/duty');
	if(preg_match_all('#<tr class="TableData">(.*?)</tr>#is',$data3,$matches)) {
		$type_int = TYPE-1;
		$check_arr = explode('</td>',$matches[1][$type_int]);
		//$check_in = array_pop($check_arr);
		$check_str = iconv('GBK','UTF-8',$check_arr[4]);
		if(false !== strpos($check_str,'已考勤')) {
			$time = trim(strip_tags($check_arr[3]));
			$msg_arr = array('result'=>true, 'msg'=>$time) ;
		}else {
			$type_str = (TYPE == AM) ? '上班' : '下班';
			$msg_arr = array('result'=>false, 'msg'=>$type_str . '未打卡') ;
		}
		
	}else {
		$msg_arr = array('result'=>false, 'msg'=>'匹配打卡记录失败') ;
	}
}
$msg = (true === $msg_arr['result']) ? "打卡成功,时间 :{$msg_arr['msg']}" : $msg_arr['msg'];
echo $msg;
if(!$fetion_remind) 	exit();

$fetion = new PHPFetion($tel,$fetion_psw);
$send_res = $fetion->toMyself($msg);
file_put_contents('log.txt',date('r') . ':' . $msg ."\r\n",FILE_APPEND);
//var_dump($send_res);
if(false !== strpos($send_res,'短信发送成功')) {
	echo "  -> 飞信消息发送成功";
}
/*
//打卡失败才给自己发短信
if(false !== $msg_arr['result']) {
	$fetion = new PHPFetion('13556152752','7340985cxm');
	$send_res = $fetion->toMyself($msg_arr['msg']);
	
}
*/