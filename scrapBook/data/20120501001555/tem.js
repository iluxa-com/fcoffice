//PNG����͸��
var arVersion = navigator.appVersion.split("MSIE")
var version = parseFloat(arVersion[1])

function fixPNG(myImage) 
{
    if ((version >= 5.5) && (version < 7) && (document.body.filters)) 
    {
       var img = myImage
	   var imgID = (myImage.id) ? "id='" + myImage.id + "' " : ""
	   var imgClass = (myImage.className) ? "class='" + myImage.className + "' " : ""
	   var imgTitle = (myImage.title) ?  "title='" + myImage.title  + "' " : "title='" + myImage.alt + "' "
	   var imgStyle = "display:inline-block;" + myImage.style.cssText
       if (img.align == "left") imgStyle = "float:left;" + imgStyle
       if (img.align == "right") imgStyle = "float:right;" + imgStyle
	   if (img.parentElement.href) imgStyle = "cursor:hand;" + imgStyle  
	   var strNewHTML = "<span " + imgID + imgClass + imgTitle
                  + " style=\"" + "width:" + myImage.width 
                  + "px; height:" + myImage.height 
                  + "px;" + imgStyle + ";"
                  + "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader"
                  + "(src=\'" + myImage.src + "\', sizingMethod='scale');\"></span>"
	   myImage.outerHTML = strNewHTML	  
    }
}

function GetObj(objName){
	if(document.getElementById){
		return eval('document.getElementById("' + objName + '")');
	}else if(document.layers){
		return eval("document.layers['" + objName +"']");
	}else{
		return eval('document.all.' + objName);
	}
}

function ADMenu(index){
for(var i=0;i<5;i++){
if(GetObj("ADm"+i)){
//GetObj("ADcon"+i).style.display = 'none';
GetObj("ADm"+i).className = "ADMenuOff";
}
}
if(GetObj("ADm"+index)){
//GetObj("ADcon"+index).style.display = 'block';
GetObj("ADm"+index).className = "ADMenuOn";

}

}
function expandIt(el) {
    whichEl =document.getElementById(el)
    if (whichEl.style.display ==  'none') {
     whichEl.style.display  = '';
    }
    else {
     whichEl.style.display  = 'none';
    }
    }
function expandIt2(el,e2) {
    whichEl =document.getElementById(el)
		whichE2=document.getElementById(e2)
    if (whichEl.style.display ==  'none') {
     whichEl.style.display  = '';
whichE2.style.display  = 'none';
    }
    else {
     whichEl.style.display  = 'none';
	 whichE2.style.display  = '';
    }
    }

function checkpost(itm) {	

  if(!itm.elements && itm.form){
  	itm = itm.form;
  }

	if (itm.newstitle.value=='') {
		alert('����д���ű������Ͷ��');
		itm.elements['newstitle'].focus();
		return false;
	}
	if (itm.newsfrom.value=='') {
		alert('����д������Դ����Ͷ��');
		itm.elements['newsfrom'].focus();
		return false;
	}
	if (itm.email.value=='') {
		alert('����д����EMAIL���ٷ���');
		itm.elements['email'].focus();
		return false;
	}
	if(!/(\S)+[@]{1}(\S)+[.]{1}(\w)+/.test(itm.email.value)) 
             {
                 alert("�������ʽ��ȷ�� e-mail ��ַ��");
				 itm.elements['email'].focus();
				 return false;
				 
				 }

	var _item = itm.elements['valimg'].parentNode;

	if (_item.parentNode.style.display=='none'){
					_item.parentNode.style.display='block';
					_item.childNodes[(_item.childNodes.length-1)].src='/validate.php';
					itm.elements['valimg'].value = '';		
			return false;
	}else{
		if(itm.elements['valimg'].value.length == 0){
					alert('����д��֤����ٷ���');
					itm.elements['valimg'].focus();
			return false;
		}else{
			return true;
			}
	}
}

//���������С
var status0='';
var curfontsize=12;
var curlineheight=15;
function fontZoomA(){
  if(curfontsize>8){
    document.getElementById('news_content').style.fontSize=(--curfontsize)+'pt';
   // document.getElementById('newsbody').style.lineHeight=(--curlineheight)+'pt';
  //document.getElementById('newshome').style.fontSize=(--curfontsize)+'pt';
 document.getElementById('news_content').style.lineHeight=(--curlineheight)+'pt';
  }
}
function fontZoomB(){
  if(curfontsize<64){
    document.getElementById('news_content').style.fontSize=(++curfontsize)+'pt';
 document.getElementById('news_content').style.lineHeight=(++curlineheight)+'pt';
 // document.getElementById('newshome').style.fontSize=(++curfontsize)+'pt';
// document.getElementById('newshome').style.lineHeight=(++curlineheight)+'pt';
  }
}

//�ظ�����_START
var i=1,j=1,wide=290,height=250;
var loop = 5;
var stime = 5;
var htime = 5;
var types;
var ok = 0;

function getEvent()
{  
    if(document.all)   return window.event;    
    func=getEvent.caller;        
    while(func!=null){  
        var arg0=func.arguments[0];
        if(arg0)
        {
          if((arg0.constructor==Event || arg0.constructor ==MouseEvent) || (typeof(arg0)=="object" && arg0.preventDefault && arg0.stopPropagation))
          {  
          return arg0;
          }
        }
        func=func.caller;
    }
    return null;
}


function show_test(div_id){
//alert(div_id);
if(types != 0 && ok == 0){
	document.getElementById(div_id).style.display='block';

	if(i<loop){
		document.getElementById(div_id).style.width= ((wide/(loop-1))*i)+"px";
		document.getElementById(div_id).style.height= ((height/(loop-1))*i)+"px";
		i++;
		zzzx = 1;
		setTimeout("show_test('"+div_id+"')",stime);
	}
	if(i==loop){
		j=1;
		ok = 1;
		
		
	}
	}
}



function get_test(div_id){
	if(types != 1 && ok==1){
		
		if(j<loop-1){
			document.getElementById(div_id).style.width= (wide-((wide/(loop-1))*j))+"px";
			document.getElementById(div_id).style.height= (height-((height/(loop-1))*j))+"px";
			j++;
			
			setTimeout("get_test('"+div_id+"')",htime)
		}
		if(j==loop-1){
			document.getElementById(div_id).style.display='none';
			i=1;
			ok = 0;
			
		}
		
	}
}

function hide_test(div_id){
	types = 0;
	
	setTimeout("get_test('"+div_id+"')",450);	
}
function s_test(div_id){
	types = 1;
	setTimeout("show_test('"+div_id+"')",450);
}

function reloadcode(ReplyID){ 
	GetObj('safecode'+ReplyID).src = '/validate1.php?'+ Math.random(); ;
} 

//show replyDiv By Y2kSaTaN
var ReTitle = '';
function _ShowReply(ReplyID,sID){
			var ReplyDiv = GetObj('Reply'+ReplyID);
			GetObj('AxajTip3'+ReplyID).style.display='none';
			ReplyDiv.style.display='block';
			

			if(ReplyDiv.innerHTML.length == 0){
				replydivHtml = '<div class="Header"><div class="replytitle">���ٻظ�������</div></div>';
				//replydivHtml += '<form name="comment' + ReplyID + '" action="/comment.php" method="post" onsubmit="return checkReply(this,'+ReplyID+')">'
				replydivHtml += '<input name="tid" type="hidden"  value="'+ ReplyID +'" /><input type="hidden" name="sid" value="' + sID + '"/>'
				replydivHtml += '<div class="Content">'
	      	 	replydivHtml += '<div class="row"><label>��������:</label><input class="input" name="nowsubject" type="text" id="subject' + ReplyID + '" value="' + ReTitle + '"/></div>'
	      	 	replydivHtml += '<div class="row"><label>���Ĵ���:</label><input class="input" name="nowname" type="text" id="name' + ReplyID + '" value="' + readCookie('cbGuestName') + '"/></div>'
	      	 	replydivHtml += '<div class="row"><label>������ҳ:</label><input class="input" name="nowpage" type="text" id="page' + ReplyID + '" value="http://"/></div>'
	      	 	replydivHtml += '<div class="row"><label>��������:</label><input class="input" name="nowemail" type="text" id="email' + ReplyID + '"/></div>'
	      	 	replydivHtml += '<div class="row"><label>��������:</label><textarea name="comment" id="comment' + ReplyID + '" onfocus="_Showvaldiv(\'' + ReplyID + '\');"></textarea></div>'
	      	 	replydivHtml += '<div class="row" style="display:none;" id="valdiv' + ReplyID + '"><label>����֤��:</label><input type="text" class="input" name="valimg" id="valimg' + ReplyID + '" style="width: 40px;"/><img src="" name="safecode" style="cursor:pointer"  border="0" align="absbottom" id="safecode' + ReplyID + '" alt="����һ����֤��ͼƬ" onclick="reloadcode(\''+ ReplyID + '\');"> [<a href="javascript:reloadcode(\'' + ReplyID + '\');">ˢ����֤��</a>] [<a href="javascript:fixCode(\'' + ReplyID + '\');">��������</a>]</div>'
	      	 	//<span class="tip" id="tip_comment' + ReplyID + '"></span>
	      	 	replydivHtml += '<div id="AxajTip' + ReplyID + '" class="AjaxTipWarning"></div>'
	      	 	replydivHtml += '<div class="row" id="bottom' + ReplyID + '" style="text-align:right;"><input type="button" class="button" value="�ݽ�" id="button' + ReplyID + '" onClick="_postReply(\'' + ReplyID + '\',' + sID +')"/><input type="button" class="button" value="�رտ��ٻظ�" onclick="_HideReply(\'' + ReplyID +'\')"></div></div>'
	      	 	//replydivHtml += '</form>';
	      	 	//���������ر���
	      	 	if (isNaN(ReplyID)){
	      	 		ReplyDiv.style.width='320px';
	      	 		replydivHtml = replydivHtml.replace(/\<input class="input"/g,'<input class="input" style="width: 150px;"');
	      	 		replydivHtml = replydivHtml.replace('<textarea name="comment"','<textarea name="comment" style="width: 200px;"');
	      	 		}
			
	      	 	ReplyDiv.innerHTML = replydivHtml;
	      	 	
      	 	}else{
      	 		//_HideReply(ReplyID);
      	 		}
}

//��ʾ��֤��
function _Showvaldiv(ReplyID){
		var Valdiv = GetObj('valdiv'+ReplyID);
		if (Valdiv.style.display=='none'){
			Valdiv.style.display='block';
			reloadcode(ReplyID);
			}
	}

//���ؿ��ٻظ���
function _HideReply(ReplyID){
	var ReplyDiv = GetObj('Reply'+ReplyID);
	ReplyDiv.style.display='none';
	//ReplyDiv.innerHTML = '';
}

//Axaj�ظ�
function _postReply(ReplyID,sID) {
	GetObj('AxajTip3'+ReplyID).style.display = 'none';
    GetObj('AxajTip'+ReplyID).style.display = 'none';

		var inobj = GetObj('email'+ReplyID);
		if (!(inobj.value=='')) {
		if(!/(\S)+[@]{1}(\S)+[.]{1}(\w)+/.test(inobj.value)){
               _ShowAxajTip('AxajTip'+ReplyID,'�������ʽ��ȷ�� e-mail ��ַ��','AjaxTipWarning');
				 inobj.focus(); return false;}
		}

		var inobj = GetObj('comment'+ReplyID);
		if(inobj.value==''){	_ShowAxajTip('AxajTip'+ReplyID,'����д���ۺ��ٷ���','AjaxTipWarning');inobj.focus();return false;}

		var inobj = GetObj('valimg'+ReplyID);
		if(inobj.value==''){	_ShowAxajTip('AxajTip'+ReplyID,'����д��֤����ٷ���','AjaxTipWarning');inobj.focus();return false;}
	StopButton('button'+ReplyID,15);

   var x = new Ajax('AxajTip2'+ReplyID,'HTML','AjaxTipWarning');

   //tid sid valimg_main comment nowsubject
   var queryString = 'tid=' + _FixHotReplyID(ReplyID) + '&sid=' + sID + '&valimg_main=' + GetObj('valimg'+ReplyID).value + '&nowname=' + escape(GetObj('name'+ReplyID).value) + '&comment=' + escape(Replace(GetObj('comment'+ReplyID).value)) + '&nowsubject=' + GetObj('subject'+ReplyID).value + '&nowpage=' + GetObj('page'+ReplyID).value + '&nowemail=' + GetObj('email'+ReplyID).value + '';
	//����ÿ���Ϣ
	createCookie('cbGuestName', GetObj('name'+ReplyID).value, 1);
	createCookie('cbGuestPage', GetObj('page'+ReplyID).value, 1);
	createCookie('cbGuestEmail', GetObj('email'+ReplyID).value, 1);
	//alert(queryString);
	var ajaxReq = '',reqClass='AjaxTipWarning';
   x.post('/Ajax.comment.php?ver=new',queryString,function(r){
		switch(r.substr(0,1)){
		case '0':
		        ajaxReq = '��Ҫ���۵����Ų�����';
		        break;
		case '1':
		        ajaxReq = '��֤�벻��ȷ';
		        break;
		case '2':
		       ajaxReq = '30���ڲ������ٴ�����';
		        break;
		case "3":
		        ajaxReq = '����д���ۺ����ύ';
		        break;
		case "4":
		        ajaxReq = '����������������';
		        break;
		case "5":
		        ajaxReq = '�������۳ɹ�.';
		        reqClass = 'AjaxTipComplete';
		        GetObj('normal').innerHTML = r.substr(3);
		        _ShowAxajTip('AxajTip3'+ReplyID,ajaxReq,reqClass);
				try{GetObj('comment'+ReplyID).value='';GetObj('valdiv'+ReplyID).style.display='none';}catch(e){}
				return true;
		        break;
		case "6":
			    ajaxReq = 'CBFW��⵽�������˲��ʵ��ִʣ������';
		        break;
		case "7":
			    ajaxReq = '������������˺������ʾ';
		        reqClass = 'AjaxTipdelay';
		        _ShowAxajTip('AxajTip3'+ReplyID,ajaxReq,reqClass);
				try{GetObj('comment'+ReplyID).value='';GetObj('valdiv'+ReplyID).style.display='none';}catch(e){}
				return true;
		        break;
		case "8":
			    ajaxReq = '��������ԭ���������Ų��������ۣ������';
		        break;
		case "9":
				ajaxReq ='����ϵͳά����';
				break;
		default:
		        ajaxReq = 'δ֪����' + r;
		}
		_ShowAxajTip('AxajTip'+ReplyID,ajaxReq,reqClass);
 		reloadcode(ReplyID);
	});
}

function _HideAxajTip(myobj){
	myobj.parentNode.parentNode.style.display='none';
}

function _ShowAxajTip(divobj,ajaxReq,reqClass){
		var AxajTipDiv = GetObj(divobj);
		ajaxReq = '<div style="float: right"><img src="/images/'+ reqClass +'.gif" style="margin: 7px 7px;" onclick="_HideAxajTip(this);"></div><div>' + ajaxReq + '<div>';
		AxajTipDiv.className = reqClass;
		AxajTipDiv.innerHTML = ajaxReq;
		AxajTipDiv.style.display = 'block';
	}

//������Żظ��Ŀ��ٻָ�����
function _FixHotReplyID(ReplyID){
     		if (isNaN(ReplyID)){
     			return ReplyID.substr(1);
     		}else{
     			return ReplyID;
     		}
}

//��Իظ���AjaxͶƱ
function _ReplyVote(ReplyID,mode){
		GetObj('AxajTip3'+ReplyID).style.display = 'none';
	     var x = new Ajax('AxajTip2'+ReplyID,'HTML','AjaxTipWarning');

     		x.get('/Ajax.vote.php?tid=' + _FixHotReplyID(ReplyID) + '&' + mode + '=1',function(r){
     			if(r.substr(0,1) == '0'){
     				if(r.substr(1,1) == '0'){
     					//GetObj('support'+ReplyID).innerHTML = r.substr(2)
						GetObj('support'+ReplyID).innerHTML = parseInt(GetObj('support'+ReplyID).innerHTML)+1;
     					}else{
     						//GetObj('against'+ReplyID).innerHTML = r.substr(2,10)
							GetObj('against'+ReplyID).innerHTML = parseInt(GetObj('against'+ReplyID).innerHTML)+1;
     						};
				/*alert('лл���Ĳ���');
     			}else{alert('���Ѿ�Ͷ��Ʊ��');}
     			})*/
     			_ShowAxajTip('AxajTip3'+ReplyID, 'лл���Ĳ���','AjaxTipComplete');
     			}else{
     				_ShowAxajTip('AxajTip3'+ReplyID, '���Ѿ�Ͷ��Ʊ��','AjaxTipWarning');
     				}
     			})
}

//��Ծٱ���Ajax
function _ReplyReport(ReplyID){
		GetObj('AxajTip3'+ReplyID).style.display = 'none';
	     var x = new Ajax('AxajTip2'+ReplyID,'HTML','AjaxTipWarning');

     		x.get('/Ajax.report.php?tid=' +  _FixHotReplyID(ReplyID) ,function(r){
     			
				if(r.substr(0,1) == '0'){
     				
				/*alert('лл���Ĳ���');
     			}else{alert('���Ѿ�Ͷ��Ʊ��');}
     			})*/
     			_ShowAxajTip('AxajTip3'+ReplyID, '��л���ľٱ�','AjaxTipComplete');
     			}else if(r.substr(0,1) == '1'){
     				_ShowAxajTip('AxajTip3'+ReplyID, '�����ظ��ٱ�','AjaxTipWarning');
     				}
				  else if(r.substr(0,1) == '2'){
				    _ShowAxajTip('AxajTip3'+ReplyID, '�������Ѿ��ٱ��˺ܶ�Σ�лл���Ĳ��롣','AjaxTipWarning');				     
				  }
				  else {
     				_ShowAxajTip('AxajTip3'+ReplyID, '������','AjaxTipWarning');
     				}
     			})
}

//��ȡCookie
createCookie = function(nm,val,y){var exp='';if(y){var dt=new Date();dt.setTime(dt.getTime()+(y*86400000));exp='; expires='+dt.toGMTString();}document.cookie=nm+'='+escape(val)+exp+';path=/';}
//��ȡCookie
readCookie = function(nm){var m='';if(window.RegExp){var re=new RegExp(';\\s*'+nm+'=([^;]*)','i');m=re.exec(';'+document.cookie);}return(m?unescape(m[1]):'');}


//������ȡ����
function Getnews(page){
	if(GetObj('ADm'+page).className!='ADMenuOn'){
   GetObj('ADcon0').style.display = 'none';
   GetObj('ADcon1').style.display = 'block';
   ADMenu(page);
   var x = new Ajax('ADtext','HTML','');
   x.get('/pagedata'+page+'.php',function(r){
	  GetObj('ADcon1').style.display = 'none';
	  GetObj('active').innerHTML=r;
   });
	}

}

function Getcomment(method,url,rep){
  //GetObj('textloading').style.display = 'block';
  //alert(url);
  var x = new Ajax('textloading','HTML','');
  x.get(url,function(r){
   GetObj('textloading').style.display = 'none';
   GetObj('essential_main').innerHTML=r;

  });

}

function Arcomment(sid,pos){
	GetObj('loading_'+pos).style.display = 'block';
	var x = new Ajax('text_'+pos,'HTML','');
	var url = '/comment/'+pos+'/'+sid+'.html';
	 x.get(url,function(r){
   GetObj('loading_'+pos).style.display = 'none';
   GetObj(pos).innerHTML=r;

  },0);


}

function Ncomment(method,url,rep){
  //GetObj('textloading').style.display = 'block';
  //alert(url);
  var x = new Ajax('textloading','HTML','');
  x.get(url,function(r){
   GetObj('textloading').style.display = 'none';
   GetObj(rep).innerHTML=r;

  });

}

function dig(sid){

  var x = new Ajax('loading'+sid,'HTML','');
  url='/Ajax.dig.php?sid='+sid;
  
  x.get(url,function(r){
   //GetObj('dig'+sid).style.display = 'none';
   if(r.substr(0,1) == '0'){
     				
     						GetObj('dig'+sid).innerHTML = r.substr(1,10);
     		     			GetObj('action'+sid).innerHTML = 'лл����';
     			}else if(r.substr(0,1) == '1'){
					GetObj('action'+sid).innerHTML = '�Ѷ���';
     				
     				}
					else{
					GetObj('reply'+sid).innerHTML = 'δ֪';
     				
     				}

  });


}

///-----------------------------------------------------------
//Ajax��
function Ajax(statusId,recvType,statusClass,cache) {
	
	
	var aj = new Object();
	aj.statusId = GetObj(statusId);
	if(statusClass!==''){aj.statusId.className = statusClass;}
	aj.targetUrl = '';
	aj.sendString = '';
	aj.recvType = recvType ? recvType : 'HTML';//HTML XML
	aj.resultHandle = null;

	aj.createXMLHttpRequest = function() {
		var request = false;
		if(window.XMLHttpRequest) {
			request = new XMLHttpRequest();
			if(request.overrideMimeType) {
				request.overrideMimeType('text/xml');
			}
		} else if(window.ActiveXObject) {
			var versions = ['Microsoft.XMLHTTP', 'MSXML.XMLHTTP', 'Microsoft.XMLHTTP', 'Msxml2.XMLHTTP.7.0', 'Msxml2.XMLHTTP.6.0', 'Msxml2.XMLHTTP.5.0','Msxml2.XMLHTTP.4.0', 'MSXML2.XMLHTTP.3.0', 'MSXML2.XMLHTTP'];
			for(var i=0; i<versions.length; i++) {
				try {
					request = new ActiveXObject(versions[i]);
					if(request) {
						return request;
					}
				} catch(e) {
					//alert(e.message);
				}
			}
		}
		return request;
	}

	aj.XMLHttpRequest = aj.createXMLHttpRequest();

	aj.processHandle = function() {
		aj.statusId.style.display = 'block';
		if(aj.XMLHttpRequest.readyState == 1) {
			aj.statusId.innerHTML = '���ڽ�������...';
			
		} else if(aj.XMLHttpRequest.readyState == 2) {
			aj.statusId.innerHTML = '���ڷ�������...';
		} else if(aj.XMLHttpRequest.readyState == 3) {
			aj.statusId.innerHTML = '���ڽ�������...';
		} else if(aj.XMLHttpRequest.readyState == 4) {
			if(aj.XMLHttpRequest.status == 200) {
				aj.statusId.innerHTML = '���ڴ�������...';
				if(aj.recvType == 'HTML') {
					aj.resultHandle(aj.XMLHttpRequest.responseText);
				} else if(aj.recvType == 'XML') {
					aj.resultHandle(aj.XMLHttpRequest.responseXML);
				}
			aj.statusId.style.display = 'none';
			} else {
				aj.statusId.innerHTML = '';
			}
		}
	}

	aj.get = function(targetUrl, resultHandle,cache) {
		/*if(cache==''){
		cache=1;
		}
		if(cache==1){
		if (targetUrl.indexOf("?") > 0)
                {
                    targetUrl += "&randnum=" + Math.random();
                }
                else
                {
                    targetUrl += "?randnum=" + Math.random();
                }
		}*/
		aj.targetUrl = targetUrl;
		aj.XMLHttpRequest.onreadystatechange = aj.processHandle;
		aj.resultHandle = resultHandle;
		if(window.XMLHttpRequest) {
			aj.XMLHttpRequest.open('GET', aj.targetUrl);
			aj.XMLHttpRequest.send(null);
		} else {
		        aj.XMLHttpRequest.open("GET", targetUrl, true);
		        aj.XMLHttpRequest.send();
		}
	}

	aj.post = function(targetUrl, sendString, resultHandle) {
		if (targetUrl.indexOf("?") > 0)
                {
                    targetUrl += "&randnum=" + Math.random();
                }
                else
                {
                    targetUrl += "?randnum=" + Math.random();
                }
		aj.targetUrl = targetUrl;
		aj.sendString = sendString;
		aj.XMLHttpRequest.onreadystatechange = aj.processHandle;
		aj.resultHandle = resultHandle;
		aj.XMLHttpRequest.open('POST', targetUrl);
		aj.XMLHttpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		aj.XMLHttpRequest.setRequestHeader("charset","gb2312");
		aj.XMLHttpRequest.send(aj.sendString);
	}
	return aj;
}


function Replace(str){
 
   re = /%/g;             // ����������ʽģʽ��
   str = str.replace(re, "��");    //  �滻 
   re = /\+/g;
   str = str.replace(re, "��");
   return(str);                   // �����滻����ַ�����
}

function  fixCode(ReplyID)   
{   
  var   ck=document.cookie;   
  var   c;
  for   (c   in   ck)   
  {   
    c.Expires=new   Date();   
  }
  reloadcode(ReplyID);
  alert("OK");
}   


 function StopButton(id,min)
 { 
  GetObj(id).disabled='disabled';
  GetObj(id).value="��������("+min+")";
if(--min>0) setTimeout("StopButton('"+id+"',"+min+")",1000);
if(min<=0){GetObj(id).value='��������';
           GetObj(id).disabled='';}
}

function process(method,url,id){
	$("#loading").show();
	$.get(url,'',function (data){
	$("#loading").hide();
	$("#"+id).html(data);
	});
}





