<?php 
    require('../wp-load.php');
    $jData = array();

    $url = rtrim($_REQUEST['url'],'/');
    $sql = $wpdb->prepare(
        "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='old_url' AND meta_value LIKE %s",
        $url.'%'
    ); //兼容以前的不带/后缀的网址
    $check = $wpdb->get_var($sql);
    /*
    var_dump($sql);
    echo "\n<br>---------------------------------------<br/>\n";
    var_dump($check);
    */
    if(NULL !== $check) {
        $jData +=array(
            'error'=>-2,
            'mess'=>'不采集,文章已存在',
        );
        die(json_encode($jData));
    }
    
    
    