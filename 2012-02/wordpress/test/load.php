<?php 
    require('../wp-load.php');
    /*
    $ids = $wpdb->get_col(
        "SELECT ID FROM $wpdb->posts WHERE post_content='[cai-ji-ready]'"
    );
    $force_delete = TRUE;
    foreach($ids as $postid) {
        $res = wp_delete_post( $postid, $force_delete );
        var_dump($res);
    }
    */
    
    
    //$str = '煓梓';
    /*
    $str = '煓梓';
    $py = sanitize_title($str);
    var_dump($py);
    */
    $res = $wpdb->get_results(
        "SELECT term_id ,name,slug FROM $wpdb->terms WHERE slug LIKE 'Unknown%'"
     );
     foreach($res as &$obj) {
        $obj->slug = sanitize_title($obj->name);
         $sql = "UPDATE $wpdb->terms SET slug='$obj->slug' WHERE term_id='$obj->term_id'";
        $up = $wpdb->query(
            $sql
        );
        var_dump($sql,$up);
        
     }
     
    var_dump($res);
    