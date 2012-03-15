 <?php
 //管理员加载样式表和js
 //详见yqxs_bind_list函数
 
 add_action( 'admin_init', 'yqxs_plugin_admin_init' );
 function yqxs_plugin_admin_init() {
    
       /* 注册样式表 */
       /*在插件函数中使用wp_enqueue_style( 'yqxs_admin_css' );调用*/
       wp_register_style( 'yqxs_admin_css', plugins_url('/css/admin.css', __FILE__) );
       
       /* 注册js脚本 */
       /*在插件函数中使用wp_enqueue_script( 'yqxs_plugin_script' );调用*/
       wp_register_script( 'yqxs_plugin_script', plugins_url('/js/yqxs_ajax_list.js', __FILE__) );
       
        wp_register_script( 'yqxs_ajax_content_2', plugins_url('/js/yqxs_ajax_content_2.js', __FILE__) );
 }