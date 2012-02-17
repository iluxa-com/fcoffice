<?php
//add_action('the_title','urls_display');
//function urls_display(){
//        global $wp;
//        var_dump($wp->query_vars);
//        exit();
//}
require_once('Word2Py.class.php');

add_action("admin_head", "yqxs_wp_head");
function yqxs_wp_head() {
    echo "<style>.yqxs_items {display:block;}";
    echo "#post_excerpt { font-weight: normal;height: 280px;width: 360px;}";
    echo "#cover {    background: none repeat scroll 0 0 #FFEECC;border: 1px solid;float: left;margin: 25px 20px 5px 5px;padding: 15px;}";
    echo "#sbutton {margin:10px 0 0 420px;}";
    echo"</style>";
    
}


function yqxs__install(){
        $url = 'http://www.onephper.tk/yqxs/install.php';
        $http=new WP_Http();
        $header['yqxs-request-url']=get_bloginfo('wpurl');
        $response=$http->request($url,array(
		"method"=>'POST',
		"timeout"=>10,
		"user-agent"=>'yqxs',
		"headers"=>$header,
	));
	if(!is_array($response)){
		return False;
	}else {
            $resp_arr = json_decode($response['body'],true);
            if(isset($resp_arr['install_at']))
                update_option('yqxs_option_installed',True);
            else
                return False;


        }

        //file_put_contents('log.txt',date('r') . var_export($response,True));
        
}

function yqxs__deactivation() {
    if(get_option('yqxs_option_installed')) {
       update_option('yqxs_option_installed',0);
    }
     return True;
}


add_action('admin_menu', 'yqxs_options_add_page');
function yqxs_options_add_page() {
	add_menu_page('小说采集器设置','采集设置','manage_options',__FILE__,'yqxs_bind_main',(WP_PLUGIN_URL.'/'.dirname(plugin_basename (__FILE__))).'/images/favicon.png',15);
        add_submenu_page(__FILE__, '采集规则设置', '采集规则', 'manage_options',__FILE__, 'yqxs_bind_main');
        add_submenu_page(__FILE__, '采集页面', '采集页面', 'manage_options', 'yqxs_bind_sub', 'yqxs_bind_sub');
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
    if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_FILES )) {
            require_once(ABSPATH . 'wp-admin/includes/admin.php');
            $id = media_handle_upload('async-upload', $_POST['postID']);
            unset($_FILES);
    }else {
    echo <<<HEREDOC
    	<form id="file-form" name="file-form" method="POST" action="" enctype="multipart/form-data" >
		<input type="file" id="async-upload" name="async-upload" />
		<input type="hidden" name="postID" value="22" />
		<input type="submit" value="Upload" id="submit" name="submit" />
	</form>
HEREDOC;
    }
    
}


function yqxs_test() {
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

    global $wpdb;
    var_dump($wpdb->data);


}

/**
 * 对wordpress get_cat_ID函数进行了扩展，在分类不存在时自动创建，nicename使用拼音
 * @param $cat_name string 分类名
 * @param $description string 分类描述
 * @return int 分类id
 */
function yqxs_get_cat_ID($cat_name,$description='')  {
    $category_id = get_cat_ID($cat_name);
     if(!$category_id) {
             $cat_info = array(
                 'cat_name'=>$cat_name,
                 'category_description' =>$description,
                 'category_nicename' =>word2pinyin($cat_name),
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

function word2pinyin($word,$ucfirst=False,$split='') {


    $py=new Word2Py($word);
    return $py->convert($word,'utf-8',$ucfirst,$split);

}


function yqxs_bind_main() {

    if(isset($_REQUEST['option_save']) && !empty($_REQUEST['option_save'])) {
        
        if(isset($_REQUEST['yqxs_rule_title']) && !empty($_REQUEST['yqxs_rule_title'])) {
            update_option('yqxs_rule_title',$_REQUEST['yqxs_rule_title']);
        }
        if(isset($_REQUEST['yqxs_rule_author']) && !empty($_REQUEST['yqxs_rule_author'])) {
            update_option('yqxs_rule_author',$_REQUEST['yqxs_rule_author']);
        }
       

        

    }else {

    //$menu_page_url = yqxs_menu_page_url('yqxs_bind_main');
    //$menu_page_url = '?page=yqxs/function.php';
    //$menu_page_url = yqxs_menu_page_url('yqxs_bind_sub');
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

function yqxs_bind_sub() {
    
    if(isset($_POST['yqxs_url']) && !empty($_POST['yqxs_url'])) {
        //显示基本采集信息
        $yqxs = array();
        $yqxs['url'] = $_POST['yqxs_url'];
        $content = file_get_contents($_POST['yqxs_url']);
        if(strpos($content ,'charset=gb2312') !==FALSE) {
            $content = iconv('gbk','utf-8',$content);
        }
        if(preg_match('|<td.*?class="pic01"><img src=["\'](.*?)["\'].*?>|is',$content,$matches)) {
            $yqxs['img'] = $matches[1];
        }
    echo <<<HEREDOC
            <div class="wrap" style="-webkit-text-size-adjust:none;">
		<div class="icon32" id="icon-options-general"><br></div>
                <h2>小说信息</h2>
            
        <div id="cover" style="float:left"><!--h4 style="padding-left:5px">封面图片</h4--><img  src="{$yqxs['img']}"></div>
           <form id="info-form" name="file-form" method="POST" action="" enctype="multipart/form-data" style="diaplay:block;margin-right:10px">

HEREDOC;
//构造表单
        //页面标题 等到午夜 > 爱曼达·奎克 >
        if(preg_match('#<title>(.*?)</title>#iUs',$content,$matches)) {
            $title_tmp = $matches[1];
            /*
            $title_arr = explode('>',$title_tmp);
            $yqxs['title'] = $title_arr[0];
            $yqxs['author'] = $title_arr[1];
             *
             */

            list($yqxs['post_title'], $yqxs['post_author']) = preg_split('#[|>]#', str_replace(' ' ,'',$matches[1]));
            echo "<label class=\"yqxs_items\">标题:<input type=\"text\" name=\"post_title\" value=\"{$yqxs['post_title']}\"/> <a href=\"{$yqxs['url']}\" >原出处链接</a></label>";
            echo "<label class=\"yqxs_items\">作者:<input type=\"text\" name=\"post_author_name\" value=\"{$yqxs['post_author']}\"/></label>";
            
        }



        //简介
        if(preg_match('|<td width="?406"?.*?>(.*?)</tr>|is',$content,$matches)){
            $yqxs['post_excerpt'] = strip_tags($matches[1],'<br>');
             echo "<label class=\"yqxs_items\" style='vertical-align:top'><span style='display:inline;float: left;margin: 0;'>摘要:</span><textarea id='post_excerpt' name=\"post_excerpt\">{$yqxs['post_excerpt']}</textarea></label>";
        }

        
        //章节地址
        if(preg_match("#<a href='(.*?)'>在线阅读</a>#",$content,$matches)) {
            $yqxs['chapters_url'] = $yqxs['url'] . $matches[1];

            //echo "<label class=\"yqxs_items\">章节URL:<input style='width: 550px;' type=\"text\" name=\"chapters_url\" value=\"{$yqxs['chapters_url']}\"/></label>";
            echo input_label('章节URL','chapters_url',$yqxs['chapters_url'],true);
        }elseif(strpos($content,'本书暂无全文')!==FALSE) {
            $yqxs['unavailbe'] = True;
        }
        //<td width="260" align="center" valign="middle" bgcolor="#FDECF7" class="pic01"><img src="http://www.yqxs.com/data/pic2/1286337640.jpg"></td>
        //小说图片地址，仅显示用

        //分类
        //$yqxs['post_category'] = array(6,);
        
        //出版社

        if(preg_match_all('#class="p5">(.*?)</TD>#is',$content,$matches)) {

                //var_dump($matches);
                $data = array_map('clean_str',$matches[1]);
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
                echo input_label('出版社','publisher',$data[0]);
                echo input_label('小说系列','nov_series',$data[1]);
                echo input_label('系列','series',$data[2]);
                echo input_label('男主角','male',$data[3]);
                echo input_label('出版日期','pub_date',$data[4]);
                echo input_label('女主角','female',$data[5]);
                echo input_label('网站','site',$data[6]);
                echo input_label('其他人物','other',$data[7]);
                echo input_label('故事背景','background',$data[8]);
                echo input_label('地点','place',$data[9]);
                echo input_label('分类','categories',$data[10]);
                echo input_label('阅读指数','stars',$data[11]);


            }else {
                echo "<h3 style=\"color:red\">取元信息失败！</h3>";
            }

           
        echo "<div id='sbutton'>";
        if(isset( $yqxs['chapters_url'] )) {
            echo '<input type="submit" name="base_insert" class="button-primary" value="开始采集" />';
        }else {
              echo "<h3 style=\"color:red;display:inline;\">本书暂无全文</h3>";
        }
        echo '<input type="submit" name="backward" class="button-primary" value="点击返回" />';
        echo "</div>";
        echo "</form>";

        

        echo "</div>";
        //var_dump($yqxs);
        
     
        
         // wp_insert_post( $yqxs );
        

       
        


    }elseif(isset($_POST['base_insert'])) {
          //var_dump($_POST);
          $post_slug =  word2pinyin($_POST['post_title']). '-'.word2pinyin($_POST['post_author_name']);
          


        //分类检查，不存在新建
        $cat_arr = explode(',',$_POST['categories']);
        foreach ($cat_arr as $k=>$cat ) {
            $cat_arr[$k] = yqxs_get_cat_ID($cat);
        }
        
        //作者检查，不存在新建 

         //基本信息入库

    }
   
    else {

        $menu_page_url = yqxs_menu_page_url('yqxs_bind_sub');
        echo <<<HEREDOC
        <div class="wrap" style="-webkit-text-size-adjust:none;">
                    <div class="icon32" id="icon-options-general"><br></div>
                            <h2>采集子选项设置</h2>

        <form action="{$menu_page_url}" enctype="multipart/form-data" method="post">
            输入你要采集的url : <input name="yqxs_url" style="width:292px"type="text" value="http://www.yqxs.com/data/book2/B3U8L34357">
            <input type="submit" name="option_save" class="button-primary" value="确定" />
        </form>
        </div>
HEREDOC;
    }

}

function yqxs_menu_page_url($pagename, $flag=false){
	return site_url('/wp-admin/admin.php?page='.$pagename);
}


function clean_str($str) {
    //"<img src='../../images/level3.gif'>"
    if(preg_match('#level(\d+?)#i',$str,$match)) {
        return $match[1];
    }else {
        $str = trim(strip_tags(str_replace('&nbsp;',' ',$str)));
    }
    return $str;
}

function input_label ($display_text,$name,$value,$readonly=False) {
    $readonly = $readonly ? 'readonly':'false';
    return  "<label class=\"yqxs_items\"><input {$readonly} style='width: 520px;' type=\"text\" name=\"{$name}\" value=\"{$value}\"/><div style='width:80px;float:left'>{$display_text}:</div></label>";

}

/**
 * 根据用户名display_name取用户id,不存在时返回false

 */
function yqxs_get_user_id($user=''){
     $user="'".$user."'";
     global $wpdb;
     $user_id = $wpdb->get_var("SELECT ID FROM $wpdb->users WHERE display_name = $user ORDER BY ID");
     return (NULL == $user_id) ? False : $user_id;
}

/**ajax方式*/
add_action('init', 'fetch_nov');
function fetch_nov() {
    if(isset($_GET['url']) && !empty($_GET['url'])) {
        header('Content-type: text/json');
        $array = array('name'=>'falcon','age'=>2722);
        echo json_encode($array);
        die();
    }
    //echo "what happen";
    

}