<?php

    $jData=array();
    $jData['list_id'] = 1;
    $jData += array(
        'error'=>-1,
        'mess'=>'bad request', 
        'extra' => strtolower('NBA-CBA-CCTV')
    );    
    var_dump($jData);