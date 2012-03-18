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
		expires.setTime(expires.getTime() + seconds*1000 );		//ʧЧʱ�䣨��λ���룩
	else
		expires = new Date(expires.getFullYear(),expires.getMonth(),expires.getDate()+1); //��Ϊ0����24�����
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




/*******************************���ع��*******************************/
function loadad(objlist) {
	//objlist:�ַ���,Ҫ���صĹ��λID����,��,�Ű�ÿ��obj��ID�ָ�,ֻ��һ��obj���ö���
	if(!objlist) return;
	//����IE:download����
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
		//�ص�����ֻ�е����������Ϻ��ִ�У��������ѭ����
		//��ÿ���ص�����ִ��ʱobj����ѭ�����Ľ��
		//�����eval��̬������ÿ��ѭ���Ļص������е�obj��ID
		//eval("$('loadadobj').startDownload(url,function(result){set_innerHTML('"+obj.id+"',result,1000);if(result.replace(/<!--(.+)-->/gi,'')!='') $('"+obj.id+"').style.display='block';})");
	}

	//ͳ����Ϣ������ӳ�һ��ʱ�䣬��Ȼ�������λ
	setTimeout('getad("webstat")',4000);
}

function getad(objid){
	var adobj = $(objid);
	if(!adobj) return;
	var now = new Date();
	var ajaxobj = new AJAXRequest;		// ����AJAX����
	ajaxobj.method="GET";			// ��������ʽΪGET,htm�ļ�������POST
	ajaxobj.url = "/qq/"+objid+".htm?"+now.getSeconds();
	ajaxobj.async = true;
	ajaxobj.callback = function(xmlobj) {
		set_innerHTML(objid,xmlobj.responseText,1000);
		if(xmlobj.responseText.replace(/<!--(.+)-->/gi,'') != '')
			$(objid).style.display = 'block';
	}
	ajaxobj.send();

	//�����ȫ��������Ϻ���ʾ�����ι�桱��ť
	if(objid=='webstat') {
		var removead = $('removead');
		if(removead) removead.style.display = 'inline-block';
	}
}
/*******************************���ع��*******************************/

/*******************************�Ƴ����*******************************/
//�Ƴ�ָ��id�Ĺ�棬֧�ֶ��id�����Ƴ�
function removead(objlist) {
	//objlist:�ַ���,Ҫ�Ƴ��Ĺ��λID����,��,�Ű�ÿ��obj��ID�ָ�,ֻ��һ��obj���ö���
	if(!objlist) return;
	var objarray = objlist.split(",");
	for (var i=0; i<objarray.length; i++) {
		var obj = $(objarray[i]);
		if(!obj) continue;
		obj.innerHTML = "";
	}
}

//˵��:��4��������Ϊ�����ڹ����ʾǰ����ʾ�����ι�桱��ť�����ڵ�һ�ε��ʱ��ʾ�ȵ���
function removead0() {
	alert('��֧�ֱ�վ��չ���ȵ����ҳ�����й����ٵ�˰�ť��');
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
	//alert('��ҳ��󲿷ֹ�������Σ����ֹ�����ڹ�������������޷����Σ�ϣ����ϲ����վ��');
}

function hidead() {
	setCookie("hidead","yes",0,"\/");
	removead3();
	alert('��ϲ���������α�վ���й�棬�޹����Чʱ������������24������\n����Ǻ���Ψһ��������Դ������֧��3̨�߶�����ط��������ã�֧�ֱ�վ��չ����֧���±�վ���й��֮���ٴ򿪸ù��ܣ�\n');
}
function showad() {
	deleteCookie("hidead","\/");
	alert('���ѻָ����й�����ʾ����л��֧�ֺ��ã�');
}



/*******************************�Ƴ����*******************************/


/*******************************�л��б�*******************************/
function ShowList(str,n,max) {
	for (var i=1;i<=max;i++)
		$(str+i).style.display = "none";
	$(str+n).style.display = 'block';
}
/*******************************�л��б�*******************************/



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


