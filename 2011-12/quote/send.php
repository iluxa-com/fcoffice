<?php
    require_once('config.php');
    require_once('PHPFetion.php');
    $file = file_get_contents('data.xml');
    $id_file = 'id.txt';
    if(preg_match_all('#<item>.*?<description>(.*?)</description></item>#',$file,$matches)){
        $id_data = file_get_contents($id_file);
        $id_arr = explode(',',$id_data);
        $i = 0;
        do {
            $id = rand(0,count($matches[1])-1);
            ++$i;
            if($i>=count($matches[1])) {
                $failed = true;
                $id = -1;
                break ;
            }
        } while(in_array($id,$id_arr));
        //发送内容
        $msg = !isset($failed) ? $matches[1][$id] : '数据出错';
        $fetion = new PHPFetion($tel,$fetion_psw);
        $send_res = $fetion->toMyself($msg);
        //echo $msg;
        //写入id
        file_put_contents($id_file,$id.',',FILE_APPEND);
    }
    