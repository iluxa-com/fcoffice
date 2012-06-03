<?php
    $arr = array(
        '广东'=> array('广州'=>'123456','深圳'=>'123457'),
        '浙江'=>array('宁波'=>'10086','绍兴'=>'12593'),
        '北京'=>array('北京'=>'10000',),
    );
    
    $jdata = json_encode($arr);
    print_r($jdata);