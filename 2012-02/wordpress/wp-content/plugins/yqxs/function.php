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
	add_menu_page('小说采集器设置','小说采集器','administrator',__FILE__,'yqxs_bind_main',(WP_PLUGIN_URL.'/'.dirname(plugin_basename (__FILE__))).'/images/favicon.png',15);
        add_submenu_page(__FILE__, '采集子选项', '采集子选项设置', 'administrator', 'yqxs_bind_sub', 'yqxs_bind_sub');
//	add_submenu_page(__FILE__, '文章同步微博绑定', '绑定微博同步文章', 'administrator', __FILE__, 'smc_bind_weibo_sync_posts');
//	add_submenu_page(__FILE__, '文章同步设置', '社交媒体连接设置', 'administrator', 'smc_bind_weibo_option', 'smc_bind_weibo_option');
//	add_submenu_page(__FILE__, '绑定微博到现有账号', '绑定微博到此账户', 0, 'smc_bind_weibo_acount', 'smc_bind_weibo_acount');
//	add_submenu_page(__FILE__, '帮助信息', '帮助', 0, 'smc_bind_weibo_help', 'smc_bind_weibo_help');
//	add_submenu_page(__FILE__, '卸载插件', '卸载插件', 'administrator', 'smc_bind_weibo_uninstall', 'smc_bind_weibo_uninstall');
}

function yqxs_bind_main() {
    echo <<<HEREDOC
    <div class="wrap" style="-webkit-text-size-adjust:none;">
		<div class="icon32" id="icon-options-general"><br></div>
			<h2>采集设置</h2>
    </div>
HEREDOC;

}

function yqxs_bind_sub() {
   // var_dump($_POST);
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
            list($yqxs['title'], $yqxs['author']) = split('[]', $matches[1]);
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

