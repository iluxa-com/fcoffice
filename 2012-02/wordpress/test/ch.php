<?php
    require_once('../wp-load.php');
    $ch_url = 'http://www.yqxs.com/data/book2/hfEvg32636/book32636.html';
    $post_id =119;
    $ch_data = yqxs_ch2db($post_id,$ch_url);
    var_dump($ch_data);
    
function yqxs_ch2db($post_id,$ch_url) {
    $content = yqxs_file_get_contents($ch_url);
    preg_match('#<div align=center>(.*?)</div>#is',$content,$matches);
    preg_match_all('#<a href=(.*?)>(.*?)</a>#is',$matches[1],$items,PREG_SET_ORDER);
    
    $dir_url = substr($ch_url,0,strrpos($ch_url,'/')+1);
    $cid_arr = array();
    
    $record_arr = array();
    
    global $wpdb;
    foreach($items as $key=>$item) {
        $url = $dir_url . $item[1];
        $data_arr = array( 
                'post_id' => $post_id,
                'chapter_order'=>$key+1,
                'content_url'=>$url,
                'chapter_title' => $item[2],
                'create_time'=>date('Y-m-d H:i:s')
        );
        
        $id = $wpdb->get_var(
            $wpdb->prepare("SELECT id FROM $wpdb->chapters WHERE `content_url` =%s;",$url)
        );        
        
        if($id!==NULL) {
           $wpdb->update(
                $wpdb->chapters,
                $data_arr,
                array('id'=>$id),
                array( '%d' ,'%d','%s', '%s','%s' ) ,
                array('%d')
            );
            $cid = $id;
        }else {        
            $cid = $wpdb->insert( 
                $wpdb->chapters, 
                $data_arr,
                array( '%d' ,'%d','%s', '%s','%s' ) 
            );
        }
        $data_arr['id'] = $cid;
        $record_arr[] = $data_arr;
        
    }
    return $record_arr;
}
    
    
    
    
    
    