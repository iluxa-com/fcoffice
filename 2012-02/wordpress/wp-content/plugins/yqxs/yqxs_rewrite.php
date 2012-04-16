<?php
/**
 * @author:falcon_chen@qq.com
 * @date: 2012-3-12
 *
 */

//下载链接的重写

  //$newrules['down/([^/]+?)/?$'] = 'index.php?did=$matches[1]';

add_filter( 'rewrite_rules_array','yqxs_insert_rewrite_rules' );
add_filter( 'query_vars','yqxs_insert_query_vars' );
add_action( 'wp_loaded','yqxs_flush_rules' );

  // flush_rules() if our rules are not yet included
  function yqxs_flush_rules(){
  $rules = get_option( 'rewrite_rules' );
  global $wp_rewrite;
  $wp_rewrite->flush_rules();
  }


  function yqxs_insert_rewrite_rules( $rules )
  {
      $newrules = array();
      // [page/?([0-9]{1,})/?$] => index.php?&paged=$matches[1]
      http://www.yqxscaiji.tk/recent/paged/2
      //$newrules['index\.html'] ='index.php?pagename=tag&page=1';
      //$newrules['index\.html'] = 'index.php';
      //[page/?([0-9]{1,})/?$] => index.php?&paged=$matches[1]
      
      //$newrules['/?([0-9]{1,})/?$'] = 'index.php?pagename=recent&page=$matches[1]';
      $newrules['share/([0-9]{1,})/?$'] ='index.php?pagename=share&sid=$matches[1]';
      $newrules['akey/([A-Za-z])?$'] = 'index.php?pagename=akey&akey=$matches[1]';
      $newrules['akey/([A-Za-z])/page/([0-9]{1,})$'] = 'index.php?pagename=akey&akey=$matches[1]&page=$matches[2]';
      $newrules['pkey/([A-Za-z])?$'] = 'index.php?pagename=pkey&pkey=$matches[1]';
      $newrules['pkey/([A-Za-z])/page/([0-9]{1,})$'] = 'index.php?pagename=pkey&pkey=$matches[1]&page=$matches[2]';
      //$newrules['pkey/([A-Za-z])(-[0-9]{1,})?$'] = 'index.php?pagename=pkey&pkey=$matches[1]&page=$matches[2]';
      $newrules['psw/([A-Za-z])(-[0-9]{1,})?$'] = 'index.php?psw=$matches[1]&page=$matches[2]';
      $newrules['asw/([A-Za-z])$'] = 'index.php?asw=$matches[1]';
      $newrules['down/([^/]+?)/?$'] = 'index.php?dd=$matches[1]';
      $newrules['([^/]+?)(-[0-9]{1,})?\.html$'] = 'index.php?name=$matches[1]&page=$matches[2]';
      unset($rules['page/?([0-9]{1,})/?$']);
      //unset($rules['pkey/([A-Za-z])(-[0-9]{1,})?$']);
     // unset($rulesrules['pkey/([A-Za-z])?$']);
      unset($rules['share/([\w\-]+)/?$']);
      return $newrules + $rules;
  }
  
  function yqxs_insert_query_vars( $vars )
  {
      array_unshift($vars,'sid');
      array_unshift($vars,'akey');
      array_unshift($vars,'pkey');
      array_unshift($vars, 'psw');
      array_push($vars, 'asw');
      array_push($vars, 'dd');
      array_push($vars, 'paged');
      return $vars;
  }

  //下载链接的利用,重写的
//add_action('init', go_download);
add_action('parse_request','go_download') ;
function go_download($obj) {
    if(isset($obj->query_vars['dd']) && !empty($obj->query_vars['dd']) ){
        $dd = $obj->query_vars['dd'];

        
       global $wpdb;
       $post = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT $wpdb->posts.ID,post_title,display_name FROM $wpdb->posts INNER JOIN 
                $wpdb->users ON $wpdb->posts.post_author = $wpdb->users.ID
                WHERE post_name=%s",
                $dd)
       );
      
       if($post ===NULL) {
            wp_die( 'The Reasource not found', '请求的资源不存在', array( 'response' => 404) );
       }

       //如果必须分享才能下载的
       if(get_option('yqxs_must_share') == 'yes') {
            //检查相应cookie是否存在,不存在转向分享
            if(auth_share_cookie($dd) === FALSE)
                wp_redirect(share_link($post->ID));
            
       }


       $results = $wpdb->get_results(
            $wpdb->prepare(
            "SELECT chapter_title,content FROM $wpdb->chapters WHERE post_id =%d ORDER BY chapter_order ASC",$post->ID
            )
       );
       if($results === NULL) {
            wp_die( 'The Reasource temporary unavailable', '暂无全文', array( 'response' => 404) );
       }
      // var_dump($results);
       //构造下载的小说内容
       $txt = '';
       $line = '-------------------------------------------';
       $txt .= get_option('yqxs_txt_quote');
       $txt .= "《{$post->post_title}》  【作者：{$post->display_name}】\r\n\r\n";
       foreach($results as $chapter) {
           $content = str_replace("\r\n",'',$chapter->content);
            $txt .= "{$line}\r\n{$chapter->chapter_title}\r\n{$line}\r\n{$content}\r\n\r\n";
       }
       $txt .=get_option('yqxs_txt_end');
       $txt = str_replace('<br /><br />',"\r\n",$txt);
       //$txt = str_replace('　　','',$txt);
       $txt = strip_tags($txt);
       $txt = htmlspecialchars_decode($txt);       
       $txt = iconv('UTF-8','GBK//IGNORE',$txt); //转成gbk编码，可选用
        
       header("Content-type: application/octet-stream");
       //header("Content-type: application/text");
       header('Content-Disposition: attachment; filename="' . iconv('UTF-8','GBK//IGNORE',$post->post_title).'.txt"');
        
       echo $txt;

        die();
    }
}


//按小说名开头字母查找的利用

add_action('wp_head',posts_starts_with);
function posts_starts_with() {
    if($cc=get_query_var('cc')) {
        echo '开头字母'.$cc;
        die();
    }
}

//do_action_ref_array('parse_request', array(&$this));

/*
add_action('parse_request','dd') ;
function dd($obj){
    //var_dump($obj);
    //die();
    //var_dump(get_query_var('dd'));
    var_dump($obj->query_vars['dd']);
    die();
}
 
 */

//生成该分享COOKIE值
function share_cookie_name($platform,$slug) {
    if(!defined('AUTH_SALT')) return FALSE;
    switch($platform) {
        case 'qzone':
            break;
        case 'qqt':
            break;    
        case 'renren':
            break;
        case 'sina':
            break;
        default:
            return FALSE;
    }
    return md5(AUTH_SALT .$platform . $slug);

}
//验证分享相关cookie是否存在
function auth_share_cookie($slug) {
    
    $qzone = share_cookie_name('qzone',$slug);
    if(isset($_COOKIE[$qzone])) return TRUE;

    $qqt = share_cookie_name('qqt',$slug);
    if(isset($_COOKIE[$qqt])) return TRUE;

    $renren = share_cookie_name('renren',$slug);
    if(isset($_COOKIE[$renren])) return TRUE;

    $sina = share_cookie_name('sina',$slug);
    if(isset($_COOKIE[$sina])) return TRUE;

    return FALSE;
}
