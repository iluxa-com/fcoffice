<?php
//列表采集中的单篇文章入库
add_action('init','ajax_list_single');
function ajax_list_single(){
    if(isset($_REQUEST['yqxs0']) && !empty($_REQUEST['yqxs0']) && isset($_REQUEST['url']) && !empty($_REQUEST['url'])) {
        //header('HTTP/1.0 401 Unauthorized');
        $jData=array();
        if(isset($_REQUEST['list_id'])){$jData[list_id] = $_REQUEST['list_id'];} //原样返回给js调用
        if(!wp_verify_nonce($_REQUEST['yqxs_token'],'yqxs_list_action')){
            header('HTTP/1.0 401 Unauthorized');
            $jData += array(
                'error'=>-1,
                'info'=>'bad request', 
            );
            
            die(json_encode($jData));
        }
        //判断是否已经入库
        global $wpdb;
        $old_url = rtrim($_REQUEST['url'],'/'); #为兼容以前的不带/后缀的网址
        $sql = $wpdb->prepare(
            "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='old_url' AND meta_value LIKE %s",
            $old_url.'%'
        ); 
        
        $check = $wpdb->get_var($sql);
        if(NULL !== $check) {
            $jData +=array(
                'error'=>-2,
                'mess'=>'文章已存在,不采集.',
         );
        die(json_encode($jData));        
        //文章基本信息入库开始
        //获取文章页
        
        //提取信息
        //图片下载
        //文章信息入库
        //如暂无全文返回
        //获取章节所在页
        //章节信息入库
        
        die(json_encode($_REQUEST));

    }
    
    
    
    
}


add_action('init', 'check_post_exists');
function check_post_exists() {

        global $wpdb;
        
        if (isset($_REQUEST['ajax_post_title']) && !empty($_REQUEST['ajax_post_title'])) {
            $id = yqxs_post_exists($_REQUEST['ajax_post_title']);
            echo  $id?$id:0;
            //var_dump(get_query_var('yq_ajax'));
            die();
        }
}


add_action('init','cj_contents') ;
function cj_contents(){
    global $wpdb;
        
    if(!isset($_REQUEST['id']) OR empty($_REQUEST['id'])) {
        return;
    }
    //可做更高级的验证
    elseif (!isset($_REQUEST['yqxs']) OR empty($_REQUEST['yqxs'])) {
        return;
        
    }else {
        
        if(!isset($_REQUEST['url']) OR empty($_REQUEST['url'])) {
            //从id取url
            $url = $wpdb->get_var(
                $wpdb->prepare("SELECT content_url FROM $wpdb->chapters WHERE `id` =%d;", $id)
            );
            if(NULL === $url) {
                $json_data = array(
                    'error' =>-3,
                    'mesg'=>'invalid id',               
                    'id' =>(int)$_REQUEST['id'],
                );
            }
            
        }else {            
            $url = $_REQUEST['url'];
            
        }
        
        
        if(NULL !== $url) {
             $id = $_REQUEST['id'];
             //获取内容页
             $page = yqxs_file_get_contents($url);
             if($page == False) {
                $json_data  = array(
                    'error' =>-4,
                    'mesg'=>'failed to get page '.$url,
          
                    'id' =>(int)$_REQUEST['id'],
                );
             }else {
                
                preg_match('#<div id="content">(.*?)<center>#is',$page,$match) ;
                $content = $match[1];
               
                
                $store_res = $wpdb->update(
                        $wpdb->chapters,
                        array('content'=>$content),
                        array('id' => (int)$id),
                        array('%s'),
                        array('%d')
               );
               //错误提示
               /*
               var_dump($store_res);
               $wpdb->show_errors(); 
               $wpdb->print_error(); 
               die($id);
               */
               //如果更新的内容和原来的内容一样，更新也会返回0, 出错时返回的是bool(false)，要区别
               if(False ===$store_res) {
                
                    $json_data = array(
                        'error' =>-5,
                        'mesg' =>'failed to store to db',
       
                        'id' =>(int)$_REQUEST['id'],
                    );
               }else {
                    $post_id = $wpdb->get_var(
                        $wpdb->prepare(
                            "SELECT post_id FROM $wpdb->chapters WHERE `id` = %d",$id
                        )
                    );
                    
                    $sql = $wpdb->prepare(
                        "SELECT id FROM $wpdb->chapters WHERE `post_id` = %d AND (`content` IS NULL OR `content` = '')",$id
                    );
                    
                    $json_data = array(
                        'error' =>0,
                        'mesg' =>'OK', 
                      
                        'id' =>(int)$_REQUEST['id'],
                        
                    );
                    
                    $post = get_post($post_id);
                    $json_data['post_title'] = $post->post_title;
                    $json_data['permalink'] =  get_permalink($post_id);
                
                        
               
               }
                
             }
             
        }
     $json_data['url'] = $url;
     header('Content-type: text/json');
     header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
     echo json_encode($json_data);
     die();
    
    }
}