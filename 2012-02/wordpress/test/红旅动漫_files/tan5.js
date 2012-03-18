//…Ë÷√cookie
function setCookie(cname,value){
	var exp = new Date();
	exp.setTime(exp.getTime() + 2*60*60*1000);
	document.cookie=cname+"="+value+";expires="+exp.toGMTString();
}
//ªÒ»°cookie
function getCookie(name){
var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
if(arr=document.cookie.match(reg)) return unescape(arr[2]);
else return null;
}

var ck = getCookie('ad5');
if(ck==null){
var unionli_aid=2380;
document.writeln("<script type=\"text\/javascript\" src=\"http:\/\/www.u17.com\/z\/mylife\/acfun\/tcfsw.js\"><\/script>")
	setCookie('ad5',1);
}