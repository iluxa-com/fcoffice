<?php
/**
 * Template Name: share  post
 *
 * A custom page use to display the recently posts
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */


function yqxs_qq_http($url) {
       $http=new WP_Http();
        $response=$http->request($url,array(
        "method"=>'GET',
        "timeout"=>10,
        "user-agent"=>'yqxs',
        "headers"=>$header,
        ));
        var_dump($response);
       
}
function curl_ssh_get($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$html = curl_exec($ch);
		curl_close($ch);
		return $html;
 }

 function curl_get($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$html = curl_exec($ch);
		curl_close($ch);
		return $html;
	}

function curl_ssh_post($url, $values) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $values);
            
             curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            
		$html = curl_exec($ch);
		curl_close($ch);
		return $html;
}    

//对响应消息体正文的
//callback( {"client_id":"100256347","openid":"D2B5FF686606F8523BEE48B63195777E"} );
//进行解释,返回一个标准对象或数组
function parse_callback($response,$as_array=FALSE) {
        $lpos = strpos($response, "(");
        $rpos = strrpos($response, ")");
        $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
        $msg_arr = json_decode($response,$as_array);
        return $msg_arr;
}
 
 
$sid = intval(get_query_var('sid'));
if($sid<1)
    wp_die('分享的资源不存在','Share resource not exists!',array( 'response' => 404,'back_link'=>true ) );
?>
<?php query_posts('p='.$sid); if (have_posts()) :  the_post(); ?>
<?php
session_start();
$app_id = '100256347';
$app_key = 'f44a2c2e22de9cc9ff916886a572185b';
$redirect_uri = share_link($sid);    

//已经存在qq_oauth的cookie时
if( isset($_REQUEST['cookie']) && $_REQUEST['cookie']=='ok' && isset($_COOKIE['qq_oauth'])) {
    if($_REQUEST['state'] !== $_SESSION['state'] OR empty($_SESSION['state']))  
        wp_die('CSRF protected2','CSRF protected2',array('response=>504','back_link'=>true));
    
    //unset($_SESSION['state']);
    parse_str($_COOKIE['qq_oauth'],$public_args);
    $public_args['oauth_consumer_key']=$app_id;
    $excerpt = $post->post_excerpt;
    $excerpt = strip_tags(str_replace(array('　　',"&nbsp;","\r","\n"),'',$excerpt));    
    $excerpt = mb_substr(trim($excerpt),0,150).'...';
    
    $param_add_share =$public_args+array(
                //.'作品 - '.get_bloginfo('name')
                'title'=>get_the_author().'《'.$post->post_title .'》' ,
                'url'=>get_permalink($post->ID) .'?time='.time(),
                'comment'=>'喜欢这本书，没想到在这里还可以下载到全文，给力！',
                'summary'=>$excerpt,
                'images'=>yqxs_get_thumblink(),
            );

         $info = curl_ssh_post('https://graph.qq.com/share/add_share',$param_add_share,'/',get_bloginfo('url'));
    //{"ret":0,"msg":"ok","share_id":1332752248}   
    
    $info = json_decode($info);
    if($info !==NULL) {
        if($info->ret ===0){
            //分享成功,设置cookie        
          $name = share_cookie_name('qzone',$post->post_name);
          $res = setcookie($name,time(),time()+3600*7,'/',str_replace(array('http://','https://'),'',get_bloginfo('url'))); 
          yqxs_redirect('下载页','分享成功',download_link($post->post_name),3);
          die();
        }
    }
   
    //分享失败
    wp_die('分享失败! '.var_export($info,true),'share_failed',array('response=>500','back_link'=>true));
    
}

if(!isset($_REQUEST['code']) OR empty($_REQUEST['code'])) {      
    $state =$_SESSION['state']= md5(uniqid() . time());
    $auth_url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=$app_id&redirect_uri=$redirect_uri&scope=get_user_info,add_pic_t,add_idol,add_share,add_t&state=$state";    
}


    //第二步
    if(isset($_REQUEST['code']) && !empty($_REQUEST['code'])) {
        
        if($_REQUEST['state'] !== $_SESSION['state']) {
            wp_die('CSRF protected! ','CSRF protected',array('response=>401','back_link'=>true));
        }
        
    $code = $_REQUEST['code'];    
    $access_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&client_id=$app_id&client_secret=$app_key&code=$code&state=$state&redirect_uri=$redirect_uri";
     $response = curl_ssh_get($access_url);
     if(strpos($response,'access_token') ===0) {     
        parse_str($response,$params);
        //$t['access_token'] 和$t['expires_in']

        //第三步，提取open_id;
        $open_id_url = 'https://graph.qq.com/oauth2.0/me?access_token='.$params['access_token'];
        $response2 = curl_ssh_get($open_id_url);
        $user = parse_callback($response2);
        if(isset($user->error)) {
            wp_die($user->error_description,$user->error,array('response=>500','back_link'=>true));
        }else {
            
            $client_id = $user->client_id;
            $openid = $user->openid;            
            $public_args = array(
                'access_token'=>$params['access_token'],                
                'openid'=>$openid,
            );
            
            setcookie('qq_oauth',http_build_query($public_args),time() + $params['expires_in'],'/',trim(str_replace(array('http://','https://'),'',get_bloginfo('url')),'.'));
            //直接跳转，不在这里分享
            $_SESSION['state']= md5(uniqid() . time());
            $href = share_link($post->ID) .'?cookie=ok&state='.$_SESSION['state']; //防止csrf;
            header('Location:'.$href);
          }
        
                       
     }else {
        //出错
       //callback( {"error":100020,"error_description":"code is reused error"} );
        $msg = parse_callback($response);        
        if (isset($msg->error))
            wp_die($user->error.':'.$user->error_description,$user->error,array('response=>500','back_link'=>true));
        
     }
       
    }
    
?>


 <!DOCTYPE html>
<!-- Ticket #11289, IE bug fix: always pad the error page with enough characters such that it is greater than 512 bytes, even after gzip compression abcdefghijklmnopqrstuvwxyz1234567890aabbccddeeffgghhiijjkkllmmnnooppqqrrssttuuvvwwxxyyzz11223344556677889900abacbcbdcdcededfefegfgfhghgihihjijikjkjlklkmlmlnmnmononpopoqpqprqrqsrsrtstsubcbcdcdedefefgfabcadefbghicjkldmnoepqrfstugvwxhyz1i234j567k890laabmbccnddeoeffpgghqhiirjjksklltmmnunoovppqwqrrxsstytuuzvvw0wxx1yyz2z113223434455666777889890091abc2def3ghi4jkl5mno6pqr7stu8vwx9yz11aab2bcc3dd4ee5ff6gg7hh8ii9j0jk1kl2lmm3nnoo4p5pq6qrr7ss8tt9uuvv0wwx1x2yyzz13aba4cbcb5dcdc6dedfef8egf9gfh0ghg1ihi2hji3jik4jkj5lkl6kml7mln8mnm9ono
-->
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="zh-CN">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>分享作品《<?php the_title()?>》</title>
	<style type="text/css">
		html {
			background: #f9f9f9;
		}
		body {
			background: #fff;
			color: #333;
			font-family: sans-serif;
			margin: 2em auto;
			padding: 1em 2em;
			-webkit-border-radius: 3px;
			border-radius: 3px;
			border: 1px solid #dfdfdf;
			width: 700px;
		}
		#page-wrap {
			margin-top: 50px;
           
		}
		#page-wrap p {
			font-size: 14px;
			line-height: 1.5;
			margin: 25px 0 20px;
		}
		#page-wrap code {
			font-family: Consolas, Monaco, monospace;
		}
		ul li {
			margin-bottom: 10px;
			font-size: 14px ;
		}
		a {
			color: #21759B;
			text-decoration: none;
		}
		a:hover {
			color: #D54E21;
		}

		.button {
			font-family: sans-serif;
			text-decoration: none;
			font-size: 14px !important;
			line-height: 16px;
			padding: 6px 12px;
			cursor: pointer;
			border: 1px solid #bbb;
			color: #464646;
			-webkit-border-radius: 15px;
			border-radius: 15px;
			-moz-box-sizing: content-box;
			-webkit-box-sizing: content-box;
			box-sizing: content-box;
			background-color: #f5f5f5;
			background-image: -ms-linear-gradient(top, #ffffff, #f2f2f2);
			background-image: -moz-linear-gradient(top, #ffffff, #f2f2f2);
			background-image: -o-linear-gradient(top, #ffffff, #f2f2f2);
			background-image: -webkit-gradient(linear, left top, left bottom, from(#ffffff), to(#f2f2f2));
			background-image: -webkit-linear-gradient(top, #ffffff, #f2f2f2);
			background-image: linear-gradient(top, #ffffff, #f2f2f2);
		}

		.button:hover {
			color: #000;
			border-color: #666;
		}

		.button:active {
			background-image: -ms-linear-gradient(top, #f2f2f2, #ffffff);
			background-image: -moz-linear-gradient(top, #f2f2f2, #ffffff);
			background-image: -o-linear-gradient(top, #f2f2f2, #ffffff);
			background-image: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#ffffff));
			background-image: -webkit-linear-gradient(top, #f2f2f2, #ffffff);
			background-image: linear-gradient(top, #f2f2f2, #ffffff);
		}
.yqxs_thumb_single {
        float:left;
        margin-right: 10px;
}
h1 {
    color: #FF0000;
    font-size: 14px;
    margin: 0;
    padding: 0 0 5px 5px;
}
			</style>
    <script type="text/javascript">
       var _u = "<?php echo $auth_url ?>";
       var open_auth =function (){
            location.href=_u;
            //window.open(_u,'_blank','width=450,height=400');
        };
       </script>
</head>
<body id="page-wrap">
	<p>
    <?php if ( has_post_thumbnail() ):?>
     <?php
        echo get_the_post_thumbnail($post->ID, array(80,120), array('class' => 'yqxs_thumb_single')); ?>
     <?php endif?>
     
     <div style="float:left;width:500px;margin-left:20px;font-size:12px">
             <h1><?php the_title();?> - <?php the_author();?>作品</h1>
     <?php
            if ( !empty( $post->post_excerpt ) ){
                $excerpt = $post->post_excerpt;
            }elseif(!empty($post->content)) {
                $excerpt = $post->content;
            }else{
                $excerpt = '暂无描述';
            }
            
            $excerpt = str_replace(array('　　',"&nbsp;"),'',$excerpt);
            //$excerpt = preg_replace("#<br />\s+?<br />#",'<br />',$excerpt);
            $excerpt = strip_tags($excerpt);
            echo   mb_substr(trim($excerpt),0,200).'...';
            
            ?>
        </div>

        <div style="clear:both"></div>
     </p>

<p style="text-align: center;"> 
<?php
        if(isset($_COOKIE['qq_oauth'])) {
            $_SESSION['state']= md5(uniqid() . time());
            $href = share_link($post->ID) .'?cookie=ok&state='.$_SESSION['state']; //防止csrf;
            if(count(explode('?',$href)) >2) 
                $href = substr_replace($href,'&',strrpos('?'),1);
       } else {
            $href = 'javascript:open_auth()';
       }
       
?>
 
<a title="分享到QQ空间" href="<?php echo $href?>" rel="nofollow">&laquo; 分享到QQ空间</a>
 
</p>
<div style="text-align:center;font-size:12px">温馨提示：分享成功后下载自动开始。或者你可以<a title="继续阅读" href="<?php the_permalink();?>" rel="nofollow"> 继续阅读&raquo;</a></div>
</body>
</html>
<?php else:?>
<?php
    wp_die('分享的资源不存在','Share resource not exists!',array( 'response' => 404,'back_link'=>true ) );
?>
<?php endif?>