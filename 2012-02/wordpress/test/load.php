<?php 
    require('../wp-load.php');
    require('../wp-admin/includes/taxonomy.php');
    require('../wp-admin/includes/image.php');
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
    $old_url = $url;
    //获取网页内容
    $yqxs = array();
    $meta = array();
    $meta['old_url'] = $url;
    $content = yqxs_file_get_contents($url);
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
            $yqxs['chapters_url'] = rtrim($yqxs['url'], '/') . '/' . $matches[1];
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
    $post_id = wp_insert_post($post, 0);
    
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
        'mess'=>(isset($yqxs['chapters_url'] )) ? '可采全文' : '暂无全文',
        'permalink'=>get_permalink($post_id),
    );
    //var_dump($jData);
    die(json_encode($jData));
    
    
    
    