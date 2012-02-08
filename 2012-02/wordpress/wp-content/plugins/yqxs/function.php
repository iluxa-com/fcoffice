<?php

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
        $yqxs = array();
        $yqxs['url'] = $_POST['yqxs_url'];
        $content = file_get_contents($_POST['yqxs_url']);
        if(strpos($content ,'charset=gb2312') !==FALSE) {
            $content = iconv('gbk','utf-8',$content);
        }
        //页面标题 等到午夜 > 爱曼达·奎克 >
        if(preg_match('#<title>(.*?)</title>#iUs',$content,$matches)) {
            $title_tmp = $matches[1];
            /*
            $title_arr = explode('>',$title_tmp);
            $yqxs['title'] = $title_arr[0];
            $yqxs['author'] = $title_arr[1];
             *
             */

            list($yqxs['title'], $yqxs['author']) = preg_split('#[|>]#', str_replace(' ' ,'',$matches[1]));
            
        }
        //简介
        if(preg_match('|<td width="?406"?.*?>(.*?)</tr>|is',$content,$matches)){
            $yqxs['summary'] = $matches[1];
        }
        //章节地址
        if(preg_match("#<a href='(.*?)'>在线阅读</a>#",$content,$matches)) {
            $yqxs['chapters'] = $yqxs['url'] . $matches[1];
        }
        //<td width="260" align="center" valign="middle" bgcolor="#FDECF7" class="pic01"><img src="http://www.yqxs.com/data/pic2/1286337640.jpg"></td>
        //小说图片地址，仅显示用
        if(preg_match('|<td.*?class="pic01"><img src="(.*?)".*?>|is',$content,$matches)) {
            $yqxs['img'] = $matches[1];
        }

        //出版社


        var_dump($yqxs);
        

       
        


    }else {

        $menu_page_url = yqxs_menu_page_url('yqxs_bind_sub');
        echo <<<HEREDOC
        <div class="wrap" style="-webkit-text-size-adjust:none;">
                    <div class="icon32" id="icon-options-general"><br></div>
                            <h2>采集子选项设置</h2>

        <form action="{$menu_page_url}" enctype="multipart/form-data" method="post">
            输入你要采集的url : <input name="yqxs_url" style="width:292px"type="text" value="">
            <input type="submit" name="option_save" class="button-primary" value="确定" />
        </form>
        </div>
HEREDOC;
    }

}

function yqxs_menu_page_url($pagename, $flag=false){
	return site_url('/wp-admin/admin.php?page='.$pagename);
}

