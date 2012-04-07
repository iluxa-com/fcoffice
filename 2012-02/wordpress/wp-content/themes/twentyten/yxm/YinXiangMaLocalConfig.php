<?php
/********************************************************************************************
 * 根据您的站点的实际情况进行设置。
 * 您只需要设置以下5个宏定义，就可以便捷、安全的使用"印象码 - 流媒体视频广告验证码云服务平台"。
 ********************************************************************************************
 */
 
//设置您的站点key，您可以在印象码系统平台中 - 您的站点管理中获取。
define("PUBLISHER_KEY","727250fa69a06052cd93842183bd04b4");

//设置运行模式：正常或者测试
//define("YinXiangMa_MODE","live");
define("YinXiangMa_MODE", "test");

//配置本地验证码的路径。当印象码系统云服务遇到异常情况服务终止时，可以快速切换到位于您的网站服务器上的验证码系统，确保您的站点安全。
define("YinXiangMa_API_LOCAL_YZM_SERVER", "http://".get_bloginfo('url'));//这里填写您的主机地址，比如http://222.123.223.123/
define("YinXiangMa_API_LOCAL_YZM_PATH", YQURI."/yxm/YinXiangMa_Local/modules/securimage/securimage_show.php");//这里填写您本地验证码的位置，建议您将我们提供的压缩包解压到网站根目录下
define("YinXiangMa_API_LOCAL_YZM_VALID_PATH", YQURI."/yxm/YinXiangMa_Local/local.valid.php");//这里为本地验证码校验页面位置
?>