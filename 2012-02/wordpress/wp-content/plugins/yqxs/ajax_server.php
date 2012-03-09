<?php
add_action('init', 'check_post_exists');
function check_post_exists() {

        global $wpdb;
        
        if (isset($_REQUEST['ajax_post_title']) && !empty($_REQUEST['ajax_post_title'])) {
            $sql = $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_title=%s ORDER BY ID DESC", 
            $_REQUEST['ajax_post_title'] ) ;
        
            $id = $wpdb->get_var($sql);
            echo  $id?$id:0;
            //var_dump(get_query_var('yq_ajax'));
            die();
        }
}