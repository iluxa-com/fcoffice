function $(id) {
	return document.getElementById(id);
}

function RndNum(min,max) { 
	return Math.floor(Math.random()*(max-min+1)+min);
}

function AJAXRequest() {
	var xmlObj = false;
	var CBfunc,ObjSelf;
	ObjSelf=this;
	try { xmlObj=new XMLHttpRequest; }
	catch(e) {
		try { xmlObj=new ActiveXObject("MSXML2.XMLHTTP"); }
		catch(e2) {
			try { xmlObj=new ActiveXObject("Microsoft.XMLHTTP"); }
			catch(e3) { xmlObj=false; }
		}
	}

	if (!xmlObj) return false;
	this.method="POST";
	this.url;
	this.async=true;
	this.content="";
	this.callback=function(cbobj) {return;}
	this.abort=function() { if(xmlObj) xmlObj.abort(); }
	this.send=function() {
		if(!this.method||!this.url||!this.async) return false;
		xmlObj.open (this.method, this.url, this.async);
		if(this.method=="POST") xmlObj.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xmlObj.onreadystatechange=function() {
			if(xmlObj.readyState==4) {
				if(xmlObj.status==200) {
					ObjSelf.callback(xmlObj);
				}
			 	delete xmlObj; 
				xmlObj=null;
			}
		}
		if(this.method=="POST") xmlObj.send(this.content);
		else xmlObj.send(null);
	}
}

function showTip(el, m, tipstr, e) { 
	var cx, cy; 
	e = window.event?window.event:e; 
	if (!e) {
		cx = window.event.x;
		cy = window.event.y;
	} 
	else {
		cx = e.clientX;
		cy = e.clientY;
	}
	m = document.getElementById(m);
	if (tipstr.length > 1) { 
		if ( m) { 
			var sl = document.documentElement.scrollLeft || document.body.scrollLeft; 
			var st = document.documentElement.scrollTop || document.body.scrollTop; 
			m.style.left = sl + cx + 10 + "px"; 
			m.style.top = st + cy + "px"; 
			m.innerHTML = ""; 
			m.innerHTML = tipstr; 
			m.style.display="";
		} 
	} 
}
function closetip(divid){
	if($(divid)) $(divid).style.display="none";
}

function setCookie(name, value, seconds, path, domain, secure) {
	var expires = new Date();
	if(seconds)
		expires.setTime(expires.getTime() + seconds*1000 );		//失效时间（单位：秒）
	else
		expires = new Date(expires.getFullYear(),expires.getMonth(),expires.getDate()+1); //设为0则当天24点过期
	var curCookie = name + "=" + escape(value) + ((expires) ? "; expires=" + expires.toGMTString() : "") + ((path) ? "; path=" + path : "") + ((domain) ? "; domain=" + domain : "") + ((secure) ? "; secure" : "");
	document.cookie = curCookie;
}

function deleteCookie(name, path, domain) {
	if (getCookie(name))
		document.cookie = name + "=" + ((path) ? "; path=" + path : "") + ((domain) ? "; domain=" + domain : "") + "; expires=Thu, 01-Jan-70 00:00:01 GMT";
}

function getCookie(name) {
	var prefix = name + "=";
	var cookieStartIndex = document.cookie.indexOf(prefix);
	if (cookieStartIndex == -1)	return null;
	var cookieEndIndex = document.cookie.indexOf(";", cookieStartIndex + prefix.length);
	if (cookieEndIndex != -1)
		return unescape(document.cookie.substring(cookieStartIndex + prefix.length, cookieEndIndex));
	else
		return unescape(document.cookie.substring(cookieStartIndex + prefix.length));
}




/*******************************加载广告*******************************/
function loadad(objlist) {
	//objlist:字符串,要加载的广告位ID序列,用,号把每个obj的ID分隔,只有一个obj则不用逗号
	if(!objlist) return;
	//创建IE:download对象
	//var loadadobj = document.createElement("IE:DOWNLOAD");
	//loadadobj.id = "loadadobj";
	//loadadobj.style.behavior = "url(#default#download)";
	//document.body.appendChild(loadadobj);

	var adobjarray = objlist.split(",");
	for (var i=0; i<adobjarray.length; i++) {
		getad(adobjarray[i]);
		//var obj = $(objarray[i]);
		//if(!obj) continue;
		//var url = "/qq/"+obj.id.toString()+".htm"
		//回调函数只有当广告下载完毕后才执行，如果放在循环中
		//则每个回调函数执行时obj都是循环最后的结果
		//因此用eval动态的生成每次循环的回调函数中的obj的ID
		//eval("$('loadadobj').startDownload(url,function(result){set_innerHTML('"+obj.id+"',result,1000);if(result.replace(/<!--(.+)-->/gi,'')!='') $('"+obj.id+"').style.display='block';})");
	}

	//统计信息放最后，延迟一定时间，不然会与广告错位
	setTimeout('getad("webstat")',4000);
}

function getad(objid){
	var adobj = $(objid);
	if(!adobj) return;
	var now = new Date();
	var ajaxobj = new AJAXRequest;		// 创建AJAX对象
	ajaxobj.method="GET";			// 设置请求方式为GET,htm文件不能用POST
	ajaxobj.url = "/qq/"+objid+".htm?"+now.getSeconds();
	ajaxobj.async = true;
	ajaxobj.callback = function(xmlobj) {
		set_innerHTML(objid,xmlobj.responseText,1000);
		if(xmlobj.responseText.replace(/<!--(.+)-->/gi,'') != '')
			$(objid).style.display = 'block';
	}
	ajaxobj.send();

	//当广告全部加载完毕后显示“屏蔽广告”按钮
	if(objid=='webstat') {
		var removead = $('removead');
		if(removead) removead.style.display = 'inline-block';
	}
}
/*******************************加载广告*******************************/

/*******************************移除广告*******************************/
//移除指定id的广告，支持多个id批量移除
function removead(objlist) {
	//objlist:字符串,要移除的广告位ID序列,用,号把每个obj的ID分隔,只有一个obj则不用逗号
	if(!objlist) return;
	var objarray = objlist.split(",");
	for (var i=0; i<objarray.length; i++) {
		var obj = $(objarray[i]);
		if(!obj) continue;
		obj.innerHTML = "";
	}
}

//说明:这4个函数是为了能在广告显示前不显示“屏蔽广告”按钮，并在第一次点击时提示先点广告
function removead0() {
	alert('请支持本站发展，先点击本页面所有广告后再点此按钮！');
}
function removead1() {
	//removead0();
	//setTimeout(removead2,10000);
	//$('removead').onclick = removead0;
	removead3();
}
function removead2() {
	$('removead').onclick = removead3;
}
function removead3() {
	var objarray = ["1","2","3","4","5","6","7","8","9"];
	for (var i=0; i<objarray.length; i++) {
		if(!$(objarray[i])) continue;
		//$(objarray[i]).innerHTML = "";
		$(objarray[i]).outerHTML = "";
	}
	//$('removead').style.display = 'none';
	//alert('本页面大部分广告已屏蔽，部分广告由于广告商问题我们无法屏蔽，希望您喜欢本站！');
}

function hidead() {
	setCookie("hidead","yes",0,"\/");
	removead3();
	alert('恭喜，您已屏蔽本站所有广告，无广告有效时间至今天晚上24点整！\n广告是红旅唯一的收入来源，用以支撑3台高额的下载服务器费用，支持本站发展请您支持下本站所有广告之后再打开该功能！\n');
}
function showad() {
	deleteCookie("hidead","\/");
	alert('您已恢复所有广告的显示，感谢您支持红旅！');
}



/*******************************移除广告*******************************/


/*******************************切换列表*******************************/
function ShowList(str,n,max) {
	for (var i=1;i<=max;i++)
		$(str+i).style.display = "none";
	$(str+n).style.display = 'block';
}
/*******************************切换列表*******************************/



function Vpop(width, height, iframe,vpopdiv) {
	}

function updateTB() {
	try {
		
	} catch(e) {}
}

function automaxwin() {
	try {
		if(getCookie("automax")=="yes") {
			if($("automax")) $("automax").checked = true;
			window.moveTo(0,0);
			window.resizeTo(window.screen.availWidth,window.screen.availHeight);
		}
	} catch(e) {}
}


window.onload = function() {
	updateTB();
	if( getCookie("hidead")=="yes" ) removead3();
	setTimeout(automaxwin,3000);
}


