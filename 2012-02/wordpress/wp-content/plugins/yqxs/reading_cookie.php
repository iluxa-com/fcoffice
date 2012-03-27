<?php
    
    //add_action('wp-head', 'reading_cookie');
    //add_action('wp_head', 'reading_cookie',null,0);
    function reading_cookie($limit=5){
        $limit = intval($limit);
        
        if(is_single()) {
            global $post;

            $title =get_query_var('name');
            $order = get_query_var('page');            
            $url = $_SERVER['REQUEST_URI'];
            
            if(isset($_COOKIE['read_list'])) {                
                
                 parse_str($_COOKIE['read_list'],$read_list);   
                 /*
                 echo '<pre>';
                 var_dump($read_list);
                 echo '</pre>';
                */
            }
            
            //注释掉，不必一定要比原先章节大。
         // if(!isset($read_list[$title]) || $read_list[$title]['order']<=$order) {
                $read_list[$title]['order']=$order;
                $read_list[$title]['url']=$url;
                $read_list[$title]['time'] = time();
            //}
          //排序
          $read_time = array();
          foreach($read_list As $list) {
            $read_time[] = $list['time'];
          }
          array_multisort($read_time,$read_list,SORT_DESC);
          $read_list = array_reverse($read_list);
          if(count($read_list)>$limit) $read_list = array_slice($read_list,0,$limit);
          setcookie('read_list',http_build_query($read_list),time()+2592000);

        }
        

    }
    
    //解析阅读历史
function parse_history() {
       
       if(!isset($_COOKIE['read_list'])) {
            $read_list = array();     
       }
       else {
             parse_str($_COOKIE['read_list'],$read_list);   
            
       }
       if(is_single()) {
            global $post;
            $title =get_query_var('name');
            $order = get_query_var('page');            
            $url = $_SERVER['REQUEST_URI'];            
            $read_list2[$title]['order']=$order;
            $read_list2[$title]['url']=$url;
            $read_list2[$title]['time'] = time();            
            if(isset($read_list[$title])) unset($read_list[$title]);
            return array_merge($read_list2,$read_list);
       }
      return $read_list;
       
}

function get_post_by_slug($post_name, $output = OBJECT)  
{  
         global $wpdb;  
         $post = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type='post'", $post_name ));  
         if ( $post )  
             return get_post($post, $output);  
  
         return null;  
}
/*      
if(!function_exists('get_chapter_title')) {
    function get_chapter_title($post_id,$chapter_order) {
        global $wpdb;
        $chapter_title = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT chapter_title FROM $wpdb->chapters WHERE post_id=%d AND chapter_order=%d",
                $post_id,
                $chapter_order
            )
        );
        return $chapter_title;    
    }
}
*/