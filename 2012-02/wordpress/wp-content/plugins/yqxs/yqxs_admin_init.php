 <?php
 add_action( 'admin_init', 'yqxs_plugin_admin_init' );
 function yqxs_plugin_admin_init() {
       /* 注册样式表 */
       wp_register_style( 'yqxs_admin_css', plugins_url('/css/admin.css', __FILE__) );
       /*在插件函数中使用wp_enqueue_style( 'yqxs_admin_css' );调用*/
 }