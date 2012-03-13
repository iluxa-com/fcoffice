<?php
        /*
        var_dump($_SERVER['PATH_INFO']);
        
        die();
        */
        // var_dump($_SERVER['REQUEST_URI']);
        // echo "\n<br>---------------------------------------<br/>\n";
        // $self = $_SERVER['PHP_SELF'];
        // var_dump($self);
        // die();
        
        require('load.php');
        $debug = 1;
        $post_id=1;
       
	//die(add_query_arg(array('preview' => 'true'), get_permalink($post_id)));
        //var_dump($wp);
        //wp('what');
        echo "<pre>";
        //var_dump($wp);
        //var_dump($GLOBALS);
        $rules =  maybe_unserialize(get_option('rewrite_rules'));
        //print_r($rules);
        // echo home_url();
        // wp_die('fuck you');
        /*
        $id =2;
        $arr = array('1'=>'one','2'=>'tow','3'=>'three');
        foreach($arr as $k=>$v) {
            if($k==$id)
            break;
        }
        echo $v;
        */
        var_dump($GLOBALS['wp_post_types']);
        /*
        echo $matches = $rules[0];
        
        
        $query = addslashes(WP_MatchesMapRegex::apply($query, $matches));
        */
        
        