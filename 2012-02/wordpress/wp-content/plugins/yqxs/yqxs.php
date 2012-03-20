<?php
/*
Plugin Name: YQXS Caiji 
Author: Falcon Chen 
Author URI: http://www.onephper.tk
Plugin URI: http://www.onephper.tk/yqxs-caiji
Description: 为WordPress提供对www.yqxs.com的文章采集
Version: 1.0
*/
define('YQXS_VERSION', '1.0');
define('PLUGIN_AUTHOR_EMAIL', 'falcon_chen@qq.com');
require_once(dirname(__FILE__).'/function.php');
require_once(dirname(__FILE__).'/reading_cookie.php');
require_once(dirname(__FILE__).'/yqxs_page_numbers.php');
require_once(dirname(__FILE__).'/widgets.php');
register_activation_hook( __FILE__, 'yqxs__install');
register_deactivation_hook( __FILE__, 'yqxs__deactivation');
?>
