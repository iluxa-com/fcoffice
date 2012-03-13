<?php

//add_action('the_title','urls_display');
//function urls_display(){
//        global $wp;
//        var_dump($wp->query_vars);
//        exit();
//}

define('YQXS_URL', WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__)));
define('YQXS_DIR', WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)));
$upload_dir = wp_upload_dir();
define('YQXS_IMG_DIR', $upload_dir['basedir']);
define('YQXS_IMG_URL', $upload_dir['baseurl']);
define('YQXS_COVER_DIR', YQXS_IMG_DIR . '/covers');
define('YQXS_COVER_URL', YQXS_IMG_URL . '/covers');

require('yqxs_admin_init.php');
require('yqxs_bind.php');
require('Word2Py.class.php');
include('ajax_server.php');
include('helpers.php');
include('yqxs_rewrite.php');





function yqxs_test() {
    
    echo esc_url('http://www.baidu.com');
    echo "\n<br>---------------------------------------<br/>\n";
    echo esc_url('http://www.baidu.com/baidu?wd=草尼马刺&tn=monline_dg');

    //$user_id = 1;
    

    // $res = add_post_meta($user_id, 'test', 'aaa', true) ;
    //yqxs_set_user_meta($user_id);
    //$res = add_user_meta($user_id, 'test', 'fuck', true) ;
    //var_dump($res);
    //set_name_starts($user_id);
    
    
    /*
    $asw = get_userdata($user_id,  'user_login',true);
    
    var_dump($asw);
    */
    /*
    $post_id = 1;
    $post = get_post($post_id);
    var_dump($post);
     */
    /*
      var_dump($imagesize);
      echo "\n<br>---------------------------------------<br/>\n";
      var_dump($metadata);
     *
     */


    /*
      $filename = 'th2dt1.jpg';
      $path = YQXS_COVER_DIR .'/' .$filename;
      $url = YQXS_COVER_URL .'/'.$filename;

      $image_type = wp_check_filetype_and_ext( $path,$filename, null );
      $attachment = array(
      'post_mime_type' =>$image_type['type'],
      'guid' =>$url,
      'post_parent' =>1,
      'post_title' =>'测试图像附件',
      'post_content'=>'',
      );
      $id = wp_insert_attachment($attachment, $path, 1);

      if ( !is_wp_error($id) ) {
      wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $path ) );
      }
      //设置特色图像
      set_post_thumbnail(1, $id);
      var_dump($id);
     *
     */
    /*
      $result = yqxs_set_cover('30.jpg',94);
      var_dump($result);
     */
    //$s = get_intermediate_image_sizes();
    //var_dump($s);
    //echo sanitize_title('war3 ROC时代');
    //echo WP_UPLOAD_DIR;
    //down_image('http://www.yqxs.com/data/pic2/1325218490.jpg');
    //image_resize( $file, $max_w, $max_h, $crop = false, $suffix = null, $dest_path = null, $jpeg_quality = 90 )
    /*
      //wp_cache_add('global_option','hello world','yqxs',500);
      //echo wp_cache_get('global_option','yqxs');
      $post = array(
      //'ID' => [ <post id> ] //Are you updating an existing post?
      //'menu_order' => [ <order> ] //If new post is a page, sets the order should it appear in the tabs.
      'comment_status' => 'open',
      'ping_status' =>  'open',
      //'pinged' => [ ? ] //?
      'post_author' => 3,
      'post_category' => array(1,), //Add some categories.
      'post_content' => 'the full text', //The full text of the post.
      //'post_date' => [ Y-m-d H:i:s ] //The time post was made.
      //'post_date_gmt' => [ Y-m-d H:i:s ] //The time post was made, in GMT.
      'post_excerpt' => 'excerpt ',//For all your post excerpt needs.
      'post_name' => 'pingying', // The name (slug) for your post
      //'post_parent' => [ <post ID> ] //Sets the parent of the new post.
      //'post_password' => [ ? ] //password for post?
      //'post_status' => [ 'draft' | 'publish' | 'pending'| 'future' | 'private' ] //Set the status of the new post.
      'post_status' =>'publish',
      'post_title' => ' 这是标题 '.date('r'), //The title of your post.
      //'post_type' => [ 'post' | 'page' | 'link' | 'nav_menu_item' | custom post type ] //You may want to insert a regular post, page, link, a menu item or some custom post type
      //'tags_input' => [ '<tag>, <tag>, <...>' ] //For tags.
      'tags_input' =>'test,测试,文章',
      //'to_ping' => [ ? ] //?
      //'tax_input' =>  array( 'xname' => array( 'term', 'term2', 'term3' ) ),  // support for custom taxonomies.
      'tax_input' =>  array( 'taxonomy_name' => array( 'term11', 'term12', 'term13' ) ),
      );
      $insert_id = wp_insert_post( $post, 0 );
      var_dump($insert_id);
     */
    /*
      $my_post['ID'] = 36;
      $my_post['post_content'] = 'This is the updated content.';
      $my_post['post_status'] = 'publish';
      echo wp_update_post( $my_post );
     *
     */
    /*
      $category_id = get_cat_ID('小说');
      var_dump($category_id);
     *
     */
    /*
      $category_id = get_cat_ID('新建分类');
      if(!$category_id) {
      $cat_info = array(
      'cat_name'=>'新建分类',
      'category_description' =>'分类描述',
      'category_nicename' =>'pinyin',
      );
      $category_id = wp_insert_category($cat_info);
      }
      var_dump($category_id);
     */


//    $user_id = yqxs_get_user_id('陈不存在');
//    var_dump($user_id);
//        $url = 'http://m.weather.com.cn/m/pn7/weather.htm';
//        $http=new WP_Http();
//        $header['yqxs-request-url']=get_bloginfo('wpurl');
//
//        $response=$http->request($url,array(
//		"method"=>'POST',
//		"timeout"=>10,
//		"user-agent"=>'yqxs',
//		"headers"=>$header,
//	));
    //global $wpdb;
    //var_dump($wpdb->data);
    /*     * resize图像
      $time = current_time('mysql');
      $uploads = wp_upload_dir($time) ;
      $tmp_file = $uploads['path']. '/test.jpg';

      $tmp_file2 = image_resize( $tmp_file, (int) get_option('large_size_w'), (int) get_option('large_size_h'), 0, 'resized');

      //$tmp_file2 = image_resize($tmp_file,80,125,true);
      //$tmp_file2 = image_resize($tmp_file,230,320,true);
      $tmp_file2 = image_resize($tmp_file,150,240,true);
      var_dump($tmp_file2);
     */
    //var_dump(yqxs_get_user_id('陈布灭'));
}

add_action("admin_head", "yqxs_wp_head");


function yqxs_wp_head() {
    echo "<style>.yqxs_items {display:block;}";
    echo "#post_excerpt { font-weight: normal;height: 280px;width: 360px;}";
    echo "#cover {    background: none repeat scroll 0 0 #FFEECC;border: 1px solid;float: left;margin: 25px 20px 5px 5px;padding: 15px;}";
    echo "#sbutton {margin:10px 650px 0 10px;   text-align: right;}";
    echo ".ch_item{display:none;}";
    echo"</style>";
    $menu_page_url = yqxs_menu_page_url('yqxs_bind_single');
    echo '<script type="text/javascript"> var single_url ="' .$menu_page_url .'";</script>';
    yqxs_loadjs('jquery-1.6.2.min.js');
    yqxs_loadjs('yqxs_admin.js');
}

add_action('admin_head','yqxs_ajax_content');
function yqxs_ajax_content() {
    yqxs_loadjs('yqxs_ajax_content.js');
}


function yqxs__install() {
    //创建小说章节表
    global $wpdb;
    $table_name = $wpdb->prefix . 'chapters';

    $sql = "
        CREATE TABLE IF NOT EXISTS `{$table_name}` (
              `id` bigint(9) NOT NULL AUTO_INCREMENT,
              `post_id` bigint(9) NOT NULL,
              `chapter_order` int(11) NOT NULL,
              `chapter_title` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
              `content_url` varchar(100) CHARACTER SET utf8 NOT NULL,
              `content` text CHARACTER SET utf8,
              `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
              PRIMARY KEY (`id`),
              KEY `content_url` (`content_url`)
        ) ";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    //新建covers目录
    if (!is_dir(YQXS_COVER_DIR)) {
        if (!mkdir(YQXS_COVER_DIR, 0666)) {
            //创建封面目录失败
            return new WP_Error('Failed to create covers dir', __('Failed to create covers dir'));
        }
    }

    //发送安装信息
    $url = 'http://www.onephper.tk/yqxs/install.php';
    $http = new WP_Http();
    $header['yqxs-request-url'] = get_bloginfo('wpurl');
    $response = $http->request($url, array(
                "method" => 'POST',
                "timeout" => 10,
                "user-agent" => 'yqxs',
                "headers" => $header,
            ));
    if (!is_array($response)) {
        return False;
    } else {
        $resp_arr = json_decode($response['body'], true);
        if (isset($resp_arr['install_at']))
            update_option('yqxs_option_installed', True);
        else
            return False;
    }

    //file_put_contents('log.txt',date('r') . var_export($response,True));
}

function yqxs__deactivation() {
    if (get_option('yqxs_option_installed')) {
        update_option('yqxs_option_installed', 0);
    }
    return True;
}

add_action('admin_menu', 'yqxs_options_add_page');

function yqxs_options_add_page() {
    add_menu_page('小说采集器设置', '小说采集', 'manage_options', __FILE__, 'yqxs_bind_list', (WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__))) . '/images/favicon.png', 15);
    add_submenu_page(__FILE__, '列表采集', '列表采集', 'manage_options', __FILE__, 'yqxs_bind_list');
    add_submenu_page(__FILE__, '单篇采集', '单篇采集', 'manage_options', 'yqxs_bind_single', 'yqxs_bind_single');
    //wp_cache_add('global_option',$SMC,'smc');
    add_submenu_page(__FILE__, '测试', '测试页面', 'manage_options', 'yqxs_test', 'yqxs_test');
    add_submenu_page(__FILE__, '测试', '上传页面', 'manage_options', 'yqxs_upload', 'yqxs_upload');
//	add_submenu_page(__FILE__, '文章同步微博绑定', '绑定微博同步文章', 'administrator', __FILE__, 'smc_bind_weibo_sync_posts');
//	add_submenu_page(__FILE__, '文章同步设置', '社交媒体连接设置', 'administrator', 'smc_bind_weibo_option', 'smc_bind_weibo_option');
//	add_submenu_page(__FILE__, '绑定微博到现有账号', '绑定微博到此账户', 0, 'smc_bind_weibo_acount', 'smc_bind_weibo_acount');
//	add_submenu_page(__FILE__, '帮助信息', '帮助', 0, 'smc_bind_weibo_help', 'smc_bind_weibo_help');
//	add_submenu_page(__FILE__, '卸载插件', '卸载插件', 'administrator', 'smc_bind_weibo_uninstall', 'smc_bind_weibo_uninstall');
}

function yqxs_upload() {
    if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_FILES)) {
        require_once(ABSPATH . 'wp-admin/includes/admin.php');
        $id = media_handle_upload('async-upload', $_POST['postID']);
        unset($_FILES);
    } else {
        echo <<<HEREDOC
    	<form id="file-form" name="file-form" method="POST" action="" enctype="multipart/form-data" >
		<input type="file" id="async-upload" name="async-upload" />
		<input type="hidden" name="postID" value="22" />
		<input type="submit" value="Upload" id="submit" name="submit" />
	</form>
HEREDOC;
    }
}

/**
 * 对wordpress get_cat_ID函数进行了扩展，在分类不存在时自动创建，nicename使用拼音
 * @param $cat_name string 分类名
 * @param $description string 分类描述
 * @return int 分类id
 */
function yqxs_get_cat_ID($cat_name, $description='') {
    $category_id = get_cat_ID($cat_name);
    if (!$category_id) {
        $cat_info = array(
            'cat_name' => $cat_name,
            'category_description' => $description,
            'category_nicename' => word2pinyin($cat_name),
        );
        $category_id = wp_insert_category($cat_info);
    }

    return $category_id;
}

/**
 * 中文到拼音的转换
 * @param $word string  中文
 * @param $ucfirst bool 是否拼音首字母大写
 * @param $split 拼音间分隔符
 *
 * @return string
 */
function word2pinyin($word, $ucfirst=False, $split='') {

    $word = strtolower($word);
    $py = new Word2Py($word);
    return $py->convert($word, 'utf-8', $ucfirst, $split);
}


function yqxs_bind_list_bak() {

    if (isset($_REQUEST['option_save']) && !empty($_REQUEST['option_save'])) {

        if (isset($_REQUEST['yqxs_rule_title']) && !empty($_REQUEST['yqxs_rule_title'])) {
            update_option('yqxs_rule_title', $_REQUEST['yqxs_rule_title']);
        }
        if (isset($_REQUEST['yqxs_rule_author']) && !empty($_REQUEST['yqxs_rule_author'])) {
            update_option('yqxs_rule_author', $_REQUEST['yqxs_rule_author']);
        }
    } else {

        //$menu_page_url = yqxs_menu_page_url('yqxs_bind_list');
        //$menu_page_url = '?page=yqxs/function.php';
        //$menu_page_url = yqxs_menu_page_url('yqxs_bind_single');
        //$nounce_field = wp_nonce_field('manage_options',) ;
        echo <<<HEREDOC
    <div class="wrap" style="-webkit-text-size-adjust:none;">
		<div class="icon32" id="icon-options-general"><br></div>
			<h2>采集规则设置</h2>
                        <form action="" enctype="multipart/form-data" method="POST">
                         <input name="page" type="hidden" value={$menu_page_url} />
                        <label>标题 : <input name="yqxs_rule_title" style="width:292px"type="text" value="<title>(.*?)</title>"></label><br/>
                        <label>作者 : <input name="yqxs_rule_author" style="width:292px"type="text" value="<a href=.*writer/.*?>(.*?)</a>"></label><br/>
                         <input type="submit" name="option_save" class="button-primary" value="确定" />

                        </form>
    </div>
HEREDOC;
    }
}

//单篇小说采集
function yqxs_bind_single() {
    wp_enqueue_style( 'yqxs_admin_css' );
    if (isset($_REQUEST['yqxs_url']) && !empty($_REQUEST['yqxs_url'])) {
        //显示基本采集信息
        $yqxs = array();
        $yqxs['url'] = $_REQUEST['yqxs_url'];
        $content = file_get_contents($_REQUEST['yqxs_url']);
        if (strpos($content, 'charset=gb2312') !== FALSE) {
            $content = iconv('gbk', 'utf-8', $content);
        }
        if (preg_match('|<td.*?class="pic01"><img src=["\'](.*?)["\'].*?>|is', $content, $matches)) {
            $yqxs['img'] = $matches[1];
            //下载图像
            $img_file = yqxs_down_image($yqxs['img']);
            if (FALSE !== $img_file) {
                //生成两种缩略图
                $img1 = image_resize($img_file['path'], 150, 240, true, null, null, $jpeg_quality = 90);
                //$img2 = image_resize($img_file['path'], 80, 125, true, null, null, $jpeg_quality = 90);
                $yqxs['img'] = str_replace($img_file['base_name'], basename($img1), $img_file['url']);
            }
        }
        $single_url = yqxs_menu_page_url('yqxs_bind_single');
        echo <<<HEREDOC
            <div class="wrap" style="-webkit-text-size-adjust:none;">
		<div class="icon32" id="icon-options-general"><br></div>
                <h2>小说信息</h2>
            
        <div id="cover" style="float:left"><!--h4 style="padding-left:5px">封面图片</h4--><img  src="{$yqxs['img']}"></div>
           <form id="info-form" name="file-form" method="POST" action="{$single_url}" enctype="multipart/form-data" style="diaplay:block;margin-right:10px">
        <input type ="hidden" name = "old_url" value="{$yqxs['url']}" />
HEREDOC;

//构造表单
        if (FALSE !== $img_file) {
            echo "<input type='hidden' value='{$img_file['base_name']}' name='img_file'>";
        }
        //页面标题 等到午夜 > 爱曼达·奎克 >
        if (preg_match('#<title>(.*?)</title>#iUs', $content, $matches)) {
            $title_tmp = $matches[1];
            /*
              $title_arr = explode('>',$title_tmp);
              $yqxs['title'] = $title_arr[0];
              $yqxs['author'] = $title_arr[1];
             *
             */

            list($yqxs['post_title'], $yqxs['post_author']) = preg_split('#[|>]#', str_replace(' ', '', $matches[1]));
            echo "<label class=\"yqxs_items\">标题:<input type=\"text\" name=\"post_title\" value=\"{$yqxs['post_title']}\"/> <a href=\"{$yqxs['url']}\" >原出处链接</a></label>";
            echo "<label class=\"yqxs_items\">作者:<input type=\"text\" name=\"post_author_name\" value=\"{$yqxs['post_author']}\"/></label>";
        }



        //简介
        if (preg_match('|<td width="?406"?.*?>(.*?)</tr>|is', $content, $matches)) {
            $yqxs['post_excerpt'] = strip_tags($matches[1], '<br>');
            echo "<label class=\"yqxs_items\" style='vertical-align:top'><span style='display:inline;float: left;margin: 0;'>摘要:</span><textarea id='post_excerpt' name=\"post_excerpt\">{$yqxs['post_excerpt']}</textarea></label>";
        }


        //章节地址
        if (preg_match("#<a href='(.*?)'>在线阅读</a>#", $content, $matches)) {
            $yqxs['chapters_url'] = rtrim($yqxs['url'], '/') . '/' . $matches[1];

            //echo "<label class=\"yqxs_items\">章节URL:<input style='width: 550px;' type=\"text\" name=\"chapters_url\" value=\"{$yqxs['chapters_url']}\"/></label>";
            echo input_label('章节URL', 'chapters_url', $yqxs['chapters_url'], true);
        } elseif (strpos($content, '本书暂无全文') !== FALSE) {
            $yqxs['unavailbe'] = True;
        }
        //<td width="260" align="center" valign="middle" bgcolor="#FDECF7" class="pic01"><img src="http://www.yqxs.com/data/pic2/1286337640.jpg"></td>
        //小说图片地址，仅显示用
        //分类
        //$yqxs['post_category'] = array(6,);
        //出版社

        if (preg_match_all('#class="p5">(.*?)</TD>#is', $content, $matches)) {

            //var_dump($matches);
            $data = array_map('clean_str', $matches[1]);
            /*
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
              $meta['categories'] = $data[10];
              $meta['stars'] = $data[11];
             */
            echo input_label('出版社', 'publisher', $data[0]);
            echo input_label('小说系列', 'nov_series', $data[1]);
            echo input_label('系列', 'series', $data[2]);
            echo input_label('出版日期', 'pub_date', $data[4]);
            echo input_label('男主角', 'male', $data[3]);
            echo input_label('女主角', 'female', $data[5]);
            echo input_label('网站', 'site', $data[6]);
            echo input_label('其他人物', 'other', $data[7]);
            echo input_label('故事背景', 'background', $data[8]);
            echo input_label('地点', 'place', $data[9]);
            echo input_label('分类', 'categories', $data[10]);
            echo input_label('阅读指数', 'stars', $data[11]);
        } else {
            echo "<h3 style=\"color:red\">取元信息失败！</h3>";
        }


        echo "<div id='sbutton'>";
        if (!isset($yqxs['chapters_url'])) {

            echo "<h3 style=\"color:red;display:inline;\">本书暂无全文，仅可采集小说基本信息入库</h3>";
        }
        echo '<input type="submit" name="base_insert" class="button-primary" value="开始采集" />';
        echo '<input type="button" name="backward" class="button-primary" value="点击返回" onclick="history.go(-1)" />';
        echo "</div>";
        echo "</form>";



        echo "</div>";
        //var_dump($yqxs);
        // wp_insert_post( $yqxs );
    } elseif (isset($_POST['post_title'])) {

       
        $post_slug = word2pinyin($_POST['post_title']) . '-' . word2pinyin($_POST['post_author_name']);
        //$_POST['post_author_name']
        //分类检查，不存在新建
        $cat_arr = explode(',', $_POST['categories']);
        foreach ($cat_arr as $k => $cat) {
            $cat_arr[$k] = yqxs_get_cat_ID($cat);
        }

        //作者检查，不存在新建
        $author_id = yqxs_get_user_id($_POST['post_author_name']);

        $metas = array();
        $tags = array();
        if (isset($_POST['publisher']) && !empty($_POST['publisher'])) {
            $metas['publisher'] = $_POST['publisher'];
        }
        if (isset($_POST['site']) && !empty($_POST['site'])) {
            $metas['site'] = $_POST['site'];
        }
        //原链接,加/
        if (isset($_POST['old_url']) && !empty($_POST['old_url'])) {
            $metas['old_url'] = rtrim($_POST['old_url'],'/').'/';
        }

        if (isset($_POST['nov_series']) && !empty($_POST['nov_series'])) {
            $metas['nov_series'] = $_POST['nov_series'];
        }

        if (isset($_POST['series']) && !empty($_POST['series'])) {
            $metas['series'] = split_words($_POST['series']);
        }
        if (isset($_POST['pub_date']) && !empty($_POST['pub_date'])) {
            $metas['pub_date'] = $_POST['pub_date'];
        }
        if (isset($_POST['male']) && !empty($_POST['male'])) {
            $metas['male'] = explode(',', $_POST['male']);
        }
        if (isset($_POST['female']) && !empty($_POST['female'])) {
            $metas['female'] = explode(',', $_POST['female']);
        }
        if (isset($_POST['others']) && !empty($_POST['others'])) {
            $metas['others'] = explode(',', $_POST['others']);
        }
        if (isset($_POST['background']) && !empty($_POST['background'])) {
            $metas['background'] = $_POST['background'];
        }
        if (isset($_POST['place']) && !empty($_POST['place'])) {
            $metas['place'] = explode(',', $_POST['place']);
        }
        if (isset($_POST['stars']) && !empty($_POST['stars'])) {

            $metas['stars'] = is_numeric($_POST['stars'])?$_POST['stars']:0;
        }
         $metas['psw'] = strtoupper($post_slug{0});
        //无章节可采时设置的内容
        if (isset($_POST['chapters_url']) && !empty($_POST['chapters_url'])) {
            $post_content = '[cai-ji]';
        } else {
            $post_content = '[no-content]';
        }
        //meta转tag
        foreach ($metas as $meta) {
            if (is_array($meta))
                $meta = implode(',', $meta);
            $tags[] = $meta;
        }
        $tags_input = implode(',', $tags);


        //小说基本信息入库
        $post = array(
            'comment_status' => 'open',
            'ping_status' => 'open',
            'post_author' => $author_id,
            'post_category' => array_values($cat_arr), //Add some categories.
            'post_content' => $post_content, //The full text of the post.
            'post_excerpt' => $_POST['post_excerpt'], //For all your post excerpt needs.
            'post_name' => $post_slug, // The name (slug) for your post
            'post_status' => 'publish',
            'post_title' => $_POST['post_title'], //The title of your post.
            //'post_type' => [ 'post' | 'page' | 'link' | 'nav_menu_item' | custom post type ]
            'tags_input' => $tags_input, //tag 处理
        );


        if ($ID = yqxs_post_exists($_POST['post_title'])) {
            $post['ID'] = $ID; //更新文章 ，不依赖客户端提交的ID
             $post_id = wp_update_post($post);
             
        } else {
            $post_id = wp_insert_post($post, 0);
        }
        


        //图片操作,如果缩略图不存在，则新增
        //$image_meta = wp_read_image_metadata($file);
        //wp_die(var_dump(has_post_thumbnail(22)));
        
        if(!has_post_thumbnail($post_id)) {   
            $cover_result = yqxs_set_cover($_POST['img_file'], $post_id, $_POST['post_title']);
        }
        
        
        
        //meta插入
        foreach ($metas as $key => $value) {
            yqxs_set_post_meta($post_id, $key, $value);
        }

                
        echo '<div class="wrap" style="-webkit-text-size-adjust:none;"><div class="icon32" id="icon-options-general"><br></div>';        
			
                        
    


        //章节信息入库
        $permalink = get_permalink($post_id);
        
        if ($post_content === '[cai-ji]') {
             echo '<h2>采集章节内容开始，请稍等</h2><hr/>';
            $ch_arr = yqxs_ch2db($post_id, $_POST['chapters_url']);
      
            
            // echo "<pre>";
            // var_dump($ch_arr);

            // echo"</pre>";
            //die();
           // $wpurl =get_bloginfo('wpurl');
            echo '<div id="ch_list">';
           
           foreach($ch_arr as $k=>$ch)  {
                
                echo "<div class='ch_item' id='no_{$ch['id']}' rel='{$ch['content_url']}' title='{$ch['id']}'>{$ch['chapter_title']}</div>\n";
            }
            echo '<div  id="finish_mes" style="color:red;display:none">完成，发布URL:<a href="' . $permalink . '" target="_blank">' . $permalink . '</a></div>';
            

        }else {
             echo '<h2>采集章节内容暂无，仅采集文章信息</h2><hr/>';
            echo '<div id="permalink"><a href="' . $permalink . '" target="_blank">发布URL:' . $permalink . '</a></div>';
        }
        echo '</div>';

    } else {

        $menu_page_url = yqxs_menu_page_url('yqxs_bind_single');
        echo <<<HEREDOC
        <div class="wrap" style="-webkit-text-size-adjust:none;">
                    <div class="icon32" id="icon-options-general"><br></div>
                            <h2>采集单页设置</h2>

        <form action="{$menu_page_url}" enctype="multipart/form-data" method="post">
            输入你要采集的url : <input name="yqxs_url" style="width:400px"type="text" value="http://www.yqxs.com/data/book2/B3U8L34357">
            <input type="submit" name="option_save" class="button-primary" value="确定" />
        </form>
        </div>
HEREDOC;
    }
}

function yqxs_menu_page_url($pagename, $flag=false) {
    return site_url('/wp-admin/admin.php?page=' . $pagename);
}

function clean_str($str) {
    //"<img src='../../images/level3.gif'>"
    if (preg_match('#level(\d+?)#i', $str, $match)) {
        return $match[1];
    } else {
        $str = trim(strip_tags(str_replace('&nbsp;', ' ', $str)));
    }
    return $str;
}

function input_label($display_text, $name, $value, $readonly=False) {
    $readonly = $readonly ? 'readonly' : '';
    return "<label class=\"yqxs_items\"><input {$readonly} style='width: 520px;' type=\"text\" name=\"{$name}\" value=\"{$value}\"/><div style='width:80px;float:left'>{$display_text}:</div></label>";
}

/**
 * 根据用户名display_name取用户id,返回用户id

 */
function yqxs_get_user_id($display_name) {
    $display_name2 = "'" . $display_name . "'";
    global $wpdb;
    $user_id = $wpdb->get_var("SELECT ID FROM $wpdb->users WHERE display_name = $display_name2 ORDER BY ID");
    //return (NULL == $user_id) ? False : $user_id;
    if (is_numeric($user_id))
        return $user_id;
    else {
        //不存在时插入

        $py_name = word2pinyin($display_name);
        $py_name2 = "'" . $py_name . "'";
        if ($wpdb->get_var("SELECT ID FROM $wpdb->users WHERE user_login = $py_name2 ORDER BY ID")) {
            $py_name = $py_name . '_' . time();
        }

        $userdata = array(
            'user_pass' => md5(uniqid('yqxs', true)),
            'user_login' => $py_name,
            'user_nicename' => $py_name,
            'display_name' => $display_name,
            'role' => 'author',
            'user_url' => 'http://falcon.sinaapp.com',
            'last_name' => mb_substr($display_name, 0, 1, 'utf-8'),
            'first_name' => mb_substr($display_name, 1, 50, 'utf-8'),
        );
        return wp_insert_user($userdata);
    }
}

/*加入作者开头字母标识*/
add_action('profile_update','set_name_starts',10,2);
add_action('user_register','set_name_starts',10,1);
function set_name_starts($user_id,$data='') {
    $user_obj = get_userdata($user_id);
    $asw = $user_obj->user_login;
    $asw = strtoupper($asw{0});

    yqxs_set_user_meta($user_id,'asw',$asw);
}


add_action('init', 'add_chapters_tb',null,0);

function add_chapters_tb() {

    global $wpdb;
    $wpdb->tables[] = 'chapters';
    $wpdb->chapters = $wpdb->prefix . 'chapters';

    if (isset($_REQUEST['yq_ajax']) && !empty($_REQUEST['yq_ajax'])) {

        var_dump(get_query_var('yq_ajax'));
        die();
    }
}

/* * ajax方式 */



add_action('wp_loaded', 'fetch_nov');

function fetch_nov() {
    global $wpdb;
    //var_dump($wp_query);
    $rewrite_rules = get_option('rewrite_rules');

    /*
      $rewrite_rules =  get_option('rewrite_rules');
      echo "<pre>";
      print_r($rewrite_rules);
     */
    //var_dump(get_query_var('name'));
    //die();
    if (isset($_GET['down']) && !empty($_GET['down'])) {

        var_dump(get_query_var('down'));
        /*
          header('Content-type: text/json');
          $array = array('name' => 'falcon', 'age' => 2722);
          echo json_encode($array);
         */
        die();
    }
    //echo "what happen";
}

//如果下载链接要使用url重写，估计要放这里了。
/*
  add_action('wp_head','after_parse');
  function after_parse() {
  global $wp_rewrite;

  //    ["([^/]+?)(-[0-9]{1,})?\.html$"]=>"index.php?name=$matches[1]&page=$matches[2]"
  $wp_rewrite->rules ['down/(\d+)/?$'] = "index.php?n=$matches[1]";
  $wp_rewrite->flush_rules();
  var_dump($wp_rewrite);
  //var_dump(get_query_var('name'));

  }
 */

/* url运行时重写
  add_filter( 'rewrite_rules_array','my_insert_rewrite_rules' );
  add_filter( 'query_vars','my_insert_query_vars' );
  add_action( 'wp_loaded','my_flush_rules' );

  // flush_rules() if our rules are not yet included
  function my_flush_rules(){
  $rules = get_option( 'rewrite_rules' );
  global $wp_rewrite;
  $wp_rewrite->flush_rules();
  }

  // Adding a new rule
  function my_insert_rewrite_rules( $rules )
  {
  $newrules = array();
  $newrules['([^/]+?)(-[0-9]{1,})?\.html$'] = 'index.php?name=$matches[1]&page=$matches[2]';
  $newrules['down/([^/]+?)/?$'] = 'index.php?did=$matches[1]';
  return $newrules + $rules;
  }
  // Adding the id var so that WP recognizes it
  function my_insert_query_vars( $vars )
  {
  array_push($vars, 'did');
  return $vars;
  }
 */


//根据用户表的display_name返回用户id，如果用户不存在则添加
/*
  function yqxs_get_user_id($display_name) {
  global $wpdb;
  $display_name = trim($display_name);
  $user_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->users;" ) );
  var_dump($user_id);
  }
 *
 */

/**
 * 下载指定url地址
 */
function down_image($url) {
    //$url = 'http://m.weather.com.cn/m/pn7/weather.htm';
    $http = new WP_Http();
    $header['yqxs-request-url'] = get_bloginfo('wpurl');

    $response = $http->request($url, array(
                "method" => 'GET',
                "timeout" => 10,
                "user-agent" => 'yqxs',
                "headers" => $header,
            ));

    if (!$response)
        return new WP_Error('Fail to download the image', __('Fail to download the image')); //图像取失败

        $store_dir = YQXS_IMG_DIR;
    $file_name = array_pop(explode('/', $url));
    $base_name = $file_name;
    $path = $store_dir . '/covers/' . $base_name;
    $url = YQXS_IMG_URL . '/covers/' . $base_name;
    file_put_contents($path, $response['body']);

    return array(
        'path' => $path,
        'base_name' => $base_name,
        'url' => $url,
    );
}

//	$title = apply_filters('sanitize_title', $title, $raw_title, $context);
//$title = apply_filters('sanitize_title', $title, $raw_title, $context);
//拼音化标题，其实是为了插入tag时的slug自动拼音化，因为用wp_insert_post时tag的slug不能指定，于是从过滤函数着手
add_filter('sanitize_title', 'py_title', 10, 3);

function py_title($title, $raw_title, $context) {
    return word2pinyin($raw_title);
}

//缩略图尺寸自定义
add_filter('intermediate_image_sizes_advanced', 'yqxs_image_sizes', 10, 1);

function yqxs_image_sizes($sizes) {
    $sizes['thumbnail'] = array(
        'width' => 80,
        'height' => 125,
        'crop' => true,
    );
    $sizes['medium'] = array(
        'width' => 150,
        'height' => 240,
        'crop' => true,
    );
    $sizes['large'] = array(
        'width' => 390,
        'height' => 475,
        'crop' => false,
    );
    $sizes['post-thumbnail'] = array(
        'width' => 120,
        'height' => 160,
        'crop' => true,
    );
    return $sizes;
}

//删除post时同时把章节删除
add_action('after_delete_post','delete_post_chapters');
function delete_post_chapters($post_id) {
    global $wpdb;
    $wpdb->query( 
        $wpdb->prepare("DELETE FROM $wpdb->chapters WHERE post_id =%d",$post_id) 
     );
    
}

add_filter('the_content','yqxs_the_content',1,1);
function yqxs_the_content($content) {
    if(strpos($content,'[cai-ji]') !==FALSE)  {
        
        global $post;
        global $wpdb;
        $content ='';
        
        if(is_single()) {
            $page = get_query_var('page');
            $permalink = get_permalink($post->ID);
            if($page==0) {
                //显示内容简介
               $content .= "<div id='yqxs_excerpt' >" .$post->post_excerpt ."<hr/></div>";
                
                $result_arr = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT chapter_order,chapter_title FROM $wpdb->chapters WHERE `post_id` =%d ORDER BY `chapter_order` ASC",
                        $post->ID,
                        ARRAY_A 
                    )
                );
                
                $chapter_arr =array();
                foreach ($result_arr as $result) {
                    
                     $chapter_url = str_replace(
                         $post->post_name,
                         $post->post_name.'-'.$result->chapter_order,
                         $permalink
                     );
                     $chapter_title = $result->chapter_title;
                     $chapter = '<li class="yqxs_ch_item"><a href=' . $chapter_url . '>';
                     $chapter .= $chapter_title . '</a></li>';
                     $chapter_arr[] = $chapter;
                }
                //章节链接
                $chapter_str = '<ul class="yqxs_ch_list">' .implode("\n",$chapter_arr) .'</ul>';
                $content .= $chapter_str;
                
                
            }else {
                //显示指定章节数的内容
                    
                 $result_arr = $wpdb->get_row(
                    $wpdb->prepare(
                        "SELECT * FROM $wpdb->chapters WHERE `post_id` = %d AND `chapter_order`=%d",
                        $post->ID,
                        $page
                    ), ARRAY_A
                 );
                 //取下页链接，注意要检查是否存在，即是否超过最后章节
                 $next_page = $page+1;
                 $next_res = $wpdb->get_var(
                    $wpdb->prepare(
                        "SELECT id FROM $wpdb->chapters WHERE `post_id`=%d AND `chapter_order` = %d",
                        $post->ID,
                        $next_page
                    )
                 );
                 $next_link = '';
                 if($next_res !==NULL) {
                    //$next_page = () ? $next_page : '';
                    $next_link = str_replace($post->post_name,$post->post_name .'-'.$next_page, $permalink);
                 }
                 
                 //取上页链接，当为0时要处理为小说首页
                 $prev_link = '';
                 $prev_page = $page -1;
                 if($prev_page>0) {
                    $prev_link = str_replace($post->post_name,$post->post_name .'-'.$prev_page, $permalink);
                 }
                    
                 
                 $content .='<h2 class="yqxs_ch_title">'.$result_arr['chapter_title'].'</h2>';
                 $content .='<div id="yqxs_ch_content">'.$result_arr['content'] .'</div>';
                 $content .='<div class="yqxs_dir_nav">';
                 $prev_link && $content .='<a href="'.$prev_link.'">前一章</a>|' ;
                 $content .= '<a href="'.$permalink.'">小说首页</a>|' ;
                 $next_link && $content .='<a href="'.$next_link.'">后一章</a>' ;
                 $content .='</div>';
                 
              
                
              
                
            }
            
        }
        
         return $content;
    }
    
    return $content;
    
   
}

//载入css新方法 ,参考：http://codex.wordpress.org/Function_Reference/wp_enqueue_style
   add_action('wp_enqueue_scripts', 'add_yqxs_stylesheet');
    function add_yqxs_stylesheet() {
        $myStyleUrl = plugins_url('css/front.css', __FILE__); // Respects SSL, Style.css is relative to the current file
        $myStyleFile = WP_PLUGIN_DIR . '/yqxs/css/front.css';
        if ( file_exists($myStyleFile) ) {
            wp_register_style('myStyleSheets', $myStyleUrl);
            wp_enqueue_style( 'myStyleSheets');
        }
    }

