<?php
/**
 * @author:falcon_chen@qq.com
 * @date: 2012-3-23
 *
 */
require_once('../wp-load.php');
//wp_redirect('http://www.baidu.com',301);
//echo share_cookie_name('qzone','bookname');
//var_dump(auth_share_cookie('bbc'));
wp_die( 'The Reasource not found', '请求的资源不存在', array( 'response' => 404,'back_link'=>true) );
?>
