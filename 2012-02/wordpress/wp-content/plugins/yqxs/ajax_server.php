<?php
    //ABWZAAABafDPsfkY

//列表采集中的单篇文章入库
add_action('init','ajax_list_single');
function ajax_list_single(){
   
    if(isset($_REQUEST['yqxs0']) && !empty($_REQUEST['yqxs0']) && isset($_REQUEST['url']) && !empty($_REQUEST['url'])) {
        require_once(ABSPATH .'wp-admin/includes/taxonomy.php');
        require_once(ABSPATH .'wp-admin/includes/image.php');
        //header('HTTP/1.0 401 Unauthorized');
        $jData=array();
        if(isset($_REQUEST['list_id'])){$jData[list_id] = $_REQUEST['list_id'];} //原样返回给js调用
        if(!wp_verify_nonce($_REQUEST['yqxs_token'],'yqxs_list_action')){
            header('HTTP/1.0 401 Unauthorized');
            $jData += array(
                'error'=>-1,
                'mess'=>'bad request', 
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
            //这个判断文章信息同时存在，章节也要存在
            $permalink = get_permalink($check);
                        $post_id = $check;
            $check2 = $wpdb->get_var(
                $wpdb->prepare(
                "SELECT id FROM $wpdb->chapters WHERE post_id =%d",$post_id
                )
            );
            //如果 存在章节则不采集

            if($check2 !==NULL) {
                
                $jData +=array(
                    'error'=>-2,
                    'mess'=>'文章已存在,忽略.',
                    'permalink' =>$permalink,
             );
                die(json_encode($jData));  
            }else{
                //如果文章是暂无全文的,忽略
                $check3 = $wpdb->get_var(
                    $wpdb->prepare(
                        "SELECT post_content FROM $wpdb->posts WHERE ID=%d",$post_id
                    )
                );
                if($check3 === '[no-content]') {
                        $jData +=array(
                            'error'=>-2,
                            'mess'=>'基本信息已入库,出处暂无全文.',
                            'permalink' =>$permalink,
                     );                    
                     die(json_encode($jData));  
                }
                
                //如果基本信息存在，则仅入库章节
                $content = yqxs_file_get_contents($old_url);
                 if (preg_match("#<a href='(.*?)'>在线阅读</a>#", $content, $matches)) {
                $yqxs['chapters_url'] = $old_url . '/' . $matches[1];
                 $ch2Db =yqxs_ch2db($post_id, $yqxs['chapters_url']);
                 $jData +=array(
                    'error'=>0,
                    'mess'=>'仅更新章节',
                    'permalink' =>$permalink,
                  );                 
                 
                 }
                 die(json_encode($jData));  
            }
        
        
        }
        //文章基本信息入库开始
        //获取文章页
        //提取信息
        //图片下载
        //文章信息入库
        //如暂无全文返回
        //获取章节所在页
        //章节信息入库
        
    $yqxs = array();
    $meta = array();
    $meta['old_url'] = $old_url;
    $content = yqxs_file_get_contents($old_url);

    
    if($content===FALSE) {
        $jData +=array(
                'error'=>-4,
                'mess'=>'无法取得小说标题,网址输入错误?',
            );
        die(json_encode($jData));
    }
    
    //下载图像
    if (preg_match('|<td.*?class="pic01"><img src=["\'](.*?)["\'].*?>|is', $content, $matches)) {
        $cover = yqxs_down_image($matches[1]);
    }    
    
    
    //匹配标题和作者
    if (preg_match('#<title>(.*?)</title>#iUs', $content, $matches)) {
        list($yqxs['post_title'], $yqxs['post_author']) = preg_split('#[|>]#', str_replace(' ', '', $matches[1]));
   }else {
        $jData +=array(
            'error'=>-4,
            'mess'=>'无法取得小说标题,网址输入错误?',
        );
        die(json_encode($jData));
   }
   //匹配简介
    if (preg_match('|<td width="?406"?.*?>(.*?)</tr>|is', $content, $matches)) {
            $yqxs['post_excerpt'] = strip_tags($matches[1], '<br>');
 
   }else{
               $jData +=array(
                        'error'=>-5,
                        'mess'=>'无法取得小说简介,网址输入错误?',
                    );
            die(json_encode($jData));           
   }
    //章节地址
    if (preg_match("#<a href='(.*?)'>在线阅读</a>#", $content, $matches)) {
            $yqxs['chapters_url'] = rtrim($old_url, '/') . '/' . $matches[1];
            $yqxs['post_content'] = '[cai-ji-ready]';
        } elseif (strpos($content, '本书暂无全文') !== FALSE) {
            //$yqxs['unavailbe'] = True;
            $yqxs['post_content'] = '[no-content]';
    }
    //元信息
    if (preg_match_all('#class="p5">(.*?)</TD>#is', $content, $matches)) {
          $data = array_map('clean_str', $matches[1]);
          $meta['publisher'] = $data[0];
          $meta['nov_series'] = $data[1];
          $meta['series'] = $data[2];
          $meta['male'] = $data[3];
          $meta['pub_date'] = $data[4];
          $meta['female'] = $data[5];
          $meta['site'] = $data[6];
          $meta['other'] = $data[7];
          $meta['background'] = $data[8];
          $meta['place'] = $data[9];
          $yqxs['categories'] = $data[10];
          $meta['stars'] = is_numeric($data[11])?$data[11]:$data[11];
        } else {
           $jData +=array(
                        'error'=>-6,
                        'mess'=>'无法取得小说元信息,网址输入错误?',
                    );
            die(json_encode($jData));            
        }    

    //入库操作
     $post_slug = word2pinyin($yqxs['post_title'].'-'.$yqxs['post_author']) ;
    $cat_arr = explode(',',  $yqxs['categories']);    
    $cat_arr = array_map('yqxs_get_cat_ID',$cat_arr);    
    $author_id = yqxs_get_user_id($yqxs['post_author']);
    $meta['psw'] = strtoupper($post_slug{0});
    foreach ($meta as $m) {
        if (is_array($m)) $m = implode(',', $m);
        $tags[] = $m;
    }
    $tags_input = implode(',', $tags);
    
        //小说基本信息入库
    $post = array(
        'comment_status' => 'open',
        'ping_status' => 'open',
        'post_author' => $author_id,
        'post_category' => array_values($cat_arr), //Add some categories.
        'post_content' => $yqxs['post_content'], //The full text of the post.
        'post_excerpt' => $yqxs['post_excerpt'] , //For all your post excerpt needs.
        'post_name' => $post_slug, // The name (slug) for your post
        'post_status' => 'publish',
        'post_title' => $yqxs['post_title'], //The title of your post.
        //'post_type' => [ 'post' | 'page' | 'link' | 'nav_menu_item' | custom post type ]
        'tags_input' => $tags_input, //tag 处理
    );
    $post_id = yqxs_post_exists($yqxs['post_title']);
    if(FALSE !== $post_id) {
        $post['ID'] = $post_id;
        wp_update_post($post);
    }else {
        $post_id = wp_insert_post($post, 0);
    }

    if(!is_numeric($post_id)) {
        $jData+=array(
            'error'=>-7,
            'mess'=>'插入文章失败',
        );
        die(json_encode($jData));
    }
    $cover_result = yqxs_set_cover($cover['base_name'], $post_id, $yqxs['post_title']);
    foreach ($meta as $key => $value) {
        yqxs_set_post_meta($post_id, $key, $value);
    }
    
    $jData += array(
        'error' =>0,
        'mess'=>(isset($yqxs['chapters_url'] )) ? '可采全文.' : '暂无全文.',
        'permalink'=>get_permalink($post_id),
    );
    if(isset($yqxs['chapters_url'])) { //仅当存在全文时进行章节信息入库
        $ch2Db =yqxs_ch2db($post_id, $yqxs['chapters_url']);
        if(!is_array($ch2Db)) {
            $jData['error'] = -8;
            $jData['mess'].='章节信息入库失败';
        }
    }
    //var_dump($jData);
    die(json_encode($jData));


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
             //检查内容是否非空,已存在时不采集
             
                $need = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT id FROM $wpdb->chapters WHERE id=%d AND (content='' OR content IS NULL)",$id
                )
             );
            
             if($need === NULL) {
                $json_data  = array(
                    'error' =>-3,
                    'mesg'=>'page content has been fetched ',
                    'id' =>(int)$_REQUEST['id'],
                    'url' => $url,
                );
                echo json_encode($json_data);
                die();
             }
          
            
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
                    
                    $check_finish = $wpdb->get_var($wpdb->prepare(
                        "SELECT id FROM $wpdb->chapters WHERE `post_id` = %d AND (`content` IS NULL OR `content` = '')",$post_id)
                     );
                    
                    //$finish_post_bool = $check_finish ===NULL ? 'true' : 'false';

                    $json_data = array(
                        'error' =>0,
                        'mesg' =>'OK', 
                        'finish' =>($check_finish ===NULL) ? TRUE : FALSE,
                        'id' =>(int)$_REQUEST['id'],
                        
                    );
                    
                    $post = get_post($post_id);
                    $json_data['post_id'] = $post_id;
                    $json_data['post_title'] = $post->post_title;
                    $json_data['permalink'] =  get_permalink($post_id);
                    //更新文章表
                    $json_data['debug'] =array(
                        'finish' =>$json_data['finish'],
                        'ID' => $post_id,
                    );
                    if($json_data['finish'] === TRUE) {
                        $json_data['update'] = $wpdb->update(
                                $wpdb->posts,
                                array('post_content'=>'[cai-ji-ok]'),
                                array('ID' => $post_id),
                                array('%s',),
                                array('%d')
                        );
                    }
                        
               
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