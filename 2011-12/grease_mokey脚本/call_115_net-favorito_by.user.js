// ==UserScript==
// @name           Call 115 net-favoritor by press Alt+A
// @namespace      http://falcon-chen.tk/
// @description    Press Alt+A to call the 115 network favorite  to collect current page in a small header frame . （按下键盘Alt+A调出115收藏夹，并在当前页面顶部 显示一个收藏对话框）
// @include        *
// ==/UserScript==

window.addEventListener("keydown",function(e) {

	if (e.keyCode==65 && e.altKey) { //alert("115");
		var b=document,a=window,c=encodeURIComponent,u="http://sc.115.com/";	
		if(b.location.protocol==="https:"){
			a.open(u+"add?url="+c(b.location)+"&title="+c(b.title)+"&from=js_bar","js_bar","left=10,top=10,height=480px,width=600px,resizable=1,alwaysRaised=1");	 
			setTimeout("a.focus()",300);
		}
		else if(b.location.protocol==="http:"){
			var j=b.createElement("script");
			var c= new Date;
			j.charset="utf-8";
			j.src=u+ "static/js/SCJ.js?" +c;
			b.body.appendChild(j)
		}
	}
 }, false);

