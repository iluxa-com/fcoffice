<?php
    
    //add_action('wp_head', 'reading_cookie',null,0);
    function reading_cookie(){
        
        if(is_single()) {
            global $post;

            $title =get_query_var('name');
            $order = get_query_var('page');            
            $url = $_SERVER['REQUEST_URI'];
            
            if(isset($_COOKIE['read_list'])) {                
                
                 parse_str($_COOKIE['read_list'],$read_list);   
                 // echo '<pre>';
                 // var_dump($read_list);
                // echo '</pre>';
                
            }
            
          if(!isset($read_list[$title]) || $read_list[$title]['order']<=$order) {
                $read_list[$title]['order']=$order;
                $read_list[$title]['url']=$url;
                $read_list[$title]['time'] = date('Y-m-d H:i:s');
            }
          //排序
          $read_time = array();
          foreach($read_list As $list) {
            $read_time[] = $list['time'];
          }
          array_multisort($read_time,$read_list,SORT_DESC);
          $read_list = array_reverse($read_list);
          if(count($read_list)>5) $read_list = array_slice($read_list,0,5);
          setcookie('read_list',http_build_query($read_list),time()+60*60*24*30 );

        }
        

    }
    