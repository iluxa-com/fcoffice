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
      
      $newrules['/?([0-9]{1,})/?$'] = 'index.php?pagename=recent&page=$matches[1]';
      $newrules['psw/([A-Za-z])(-[0-9]{1,})?$'] = 'index.php?psw=$matches[1]&page=$matches[2]';
      $newrules['asw/([A-Za-z])$'] = 'index.php?asw=$matches[1]';
      $newrules['down/([^/]+?)/?$'] = 'index.php?dd=$matches[1]';
      $newrules['([^/]+?)(-[0-9]{1,})?\.html$'] = 'index.php?name=$matches[1]&page=$matches[2]';
      //unset($rules['page/?([0-9]{1,})/?$']);
      return $newrules + $rules;
  }
  
  function yqxs_insert_query_vars( $vars )
  {
      array_unshift($vars, 'psw');
      array_push($vars, 'asw');
      array_push($vars, 'dd');
      array_push($vars, 'paged');
      return $vars;
  }

  //下载链接的利用
add_action('wp_head', go_download);

function go_download() {
    if ($dd = get_query_var('dd')) {
        echo "down:" . $dd;
        die();
    }
    if($c=get_query_var('psw')) {
        echo '开头字母1'.$c;
        echo '<br/>页码: ' . get_query_var('page');
        die();
    }
     if($cc=get_query_var('asw')) {
        echo '开头字母2'.$cc;
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