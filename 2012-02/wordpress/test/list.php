<?php
        require('../wp-load.php');
        $wpdb->show_errors(); 
        $wpdb->print_error(); 
        
        $post_id = 543;
        $wpdb->update(
                $wpdb->posts,
                array('post_content'=>'[cai-ji-ok]'),
                array('ID' => $post_id),
                array('%s',),
                array('%d')
        );
        var_dump($res);