<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>


		
<title>QQ互联</title>
<style>
#qqLoginBtn {
    
}
</style>
<!--
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.0/jquery.min.js"></script>
<script>
$(document).ready(function(){
  console.log($('#qqLoginBtn img').attr('src','/btn-chrlist.gif'));
});
</script>
-->
</head>
<body>
<span id="qq_login_btn"></span>
<textarea rows="4" cols="25" id="weibo_ctt">#QQ互联JSSDK测试#曾经沧海难为水，除却巫山不是云。</textarea><br/>
<label><input value=" 发威 " onclick="weibo();" type="button" /></label>
<br/>
<textarea rows="3" cols="20" readonly="1" class="callback" id="weibo_back"></textarea>

</body>

<script type="text/javascript" src="http://qzonestyle.gtimg.cn/qzone/openapi/qc.js#appId=100235414" charset="utf-8"></script>

<script type="text/javascript">

	function weibo(){
		if(QC.Login.check()){
			var ctt = document.getElementById("weibo_ctt").value;
			if(!ctt) {
				alert('亲！把数据填完。。');
				return;
			}

			var weibo_back = document.getElementById("weibo_back");
			weibo_back.value = "";
			QC.api("add_t", {content:ctt})
				.success(function(s){//成功回调
					alert('发送微博成功，请到腾讯微博内查看！');
					QC.Console.log(' [S] weibo seq no. : ' + s.seq);
				})
				.error(function(f){//失败回调
					alert('发送微博失败，错误码：'+f.code);
					QC.Console.log(" [E] weibo seq no. "+f.seq);
				})
				.complete(function(c){//完成请求回调
					weibo_back.value = c.stringifyData();
				});
		}else{
			alert("请登录后体验");
                        //加入QQ登录按钮


		}
	}

	function fenxiang(){
		if(QC.Login.check()){
			var paras = {
				images:document.getElementById("fenxiang_images").value,
				title:document.getElementById("fenxiang_title").value,
				url:document.getElementById("fenxiang_url").value,
				comment:document.getElementById("fenxiang_comment").value,
				summary:document.getElementById("fenxiang_summary").value
			};

			for(var i in paras) {
				if(!(paras[i]+"")) {
					alert('亲！把数据填完。。');
					return;
				}
			}

			var fenxiang_back = document.getElementById("fenxiang_back");
			fenxiang_back.value = "";
			QC.api("add_share", paras)
				.success(function(s){//请自行改写成功回调
					alert('分享成功，请到空间内查看！');
					QC.Console.log(" [S] fenxiang seq no. "+s.seq);
				})
				.error(function(f){//请自行改写失败回调
					alert('分享失败，错误码：'+f.code);
					QC.Console.log(" [E] fenxiang seq no. "+f.seq);
				})
				.complete(function(c){//请自行改写完成请求回调
					fenxiang_back.value = c.stringifyData();
				});
		}else{
			alert("请登录后体验");
		}
	}

	function getInfo() {
		if(QC.Login.check()){
			var getInfo_back = document.getElementById("getInfo_back");
			getInfo_back.value = "";
		
			QC.api("get_user_info")
				.success(function(s){//成功回调
					alert("获取用户信息成功！当前用户昵称为："+s.data.nickname);
				})
				.error(function(f){//失败回调
					alert("获取用户信息失败！");
				})
				.complete(function(c){//完成请求回调
					alert("获取用户信息完成！");
					getInfo_back.value = c.stringifyData();
				});
		}else{
			alert("请登录后体验");
		}
	}

	function getToken() {
		if(QC.Login.check()){
			QC.Login.getMe(function(openId, accessToken){
				alert(["当前登录用户的", "openid为："+openId, "accessToken为："+accessToken].join("\n"));	
			});
			//这里可以调用自己的保存接口
			//...
		}else{
			alert("请登录后体验");
		}
	}

	//callback为jssdk保留字，测试是否会覆盖当前页面函数
	function callback(){
		alert("我很好，我很好");
	}


	function pageInit() {
		document.getElementById("qq_login_btn").innerHTML = document.getElementById("qq_login_btn").getAttribute("_origText");
	}

	//获取jsdoc示例
	function getDoc() {
		QC.api.getDoc("get_user_info", function(doc){
			var str = QC.JSON.stringify(doc);
			alert(str);
		});
	}

	pageInit();
        
        QC.Login({
            btnId:"qq_login_btn"
        });
        

</script>
</html>