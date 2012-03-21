var searchReq=createAjaxObj();
function createAjaxObj()
{
	var httprequest=false;
	if(window.XMLHttpRequest)
	{
		httprequest=new XMLHttpRequest();
		if(httprequest.overrideMimeType)
			httprequest.overrideMimeType('text/xml');
	}
	else if (window.ActiveXObject)
	{
		//IE
		try
		{
			httprequest=new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{
			try
			{
				httprequest=new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e)
			{
			}
		}
	}
	return httprequest
}

function searchSuggest()
{
	if (document.getElementById('searchword').value.length>0)
	{
		var str=escape(document.getElementById('searchword').value);
		url=$('#formsearch').attr('action')+"?str="+str+ "&t=" +  new Date().getTime();
		searchReq.open("get",url);
		searchReq.onreadystatechange=handleSearchSuggest;
		searchReq.send(null);	
	}
	else
	{
		document.getElementById("search_suggest").innerHTML="";
		document.getElementById("search_suggest").style.display="none";
	}
	
}

function handleSearchSuggest()
{
	if(searchReq.readyState==4)
	{		
			var ss=document.getElementById("search_suggest");		
			ss.innerHTML="";
			s0=searchReq.responseText.length;		
			if (s0>0)
			{
				xmldoc=searchReq.responseXML;	
				var message_nodes=xmldoc.getElementsByTagName("message");
				var n_messages=message_nodes.length;				
				if (n_messages<=0)
				{
					document.getElementById("search_suggest").innerHTML="";
					document.getElementById("search_suggest").style.display="none";
				}
			    else
				{ 
					document.getElementById("search_suggest").style.display="block";
					for (i=0;i<n_messages;i++ )
					{
						var suggest='<div onmouseover="javascript:suggestOver(this);overset(this.innerHTML);"'
						suggest+='onmouseout="javascript:sugggestOut(this);"';
						suggest+='onclick="javascript:setSearch(this.innerHTML);"';
						suggest +='class="suggest_link">'+message_nodes[i].getElementsByTagName("text")[0].firstChild.data+'</div>';
						ss.innerHTML +=suggest;
						
					}

				}
			}
			else
			{
				document.getElementById("search_suggest").innerHTML="";
				document.getElementById("search_suggest").style.display="none";
			}		
	}
	else
	{
		//alert('网络连接失败');
	}
}

function suggestOver(div_value)
{
	div_value.className='suggest_link_over';
}

function sugggestOut(div_value)
{
  div_value.className='suggest_link';
  
}

function setSearch(div_value)
{
   //document.getElementById("searchword").value=div_value;
     window.location.href = "/search.asp?searchword="+div_value;
     document.getElementById("search_suggest").style.display="none";
}
function overset(div_value)
{
        
	document.getElementById("searchword").value=div_value;
        
	}

/*
document.onclick=function(displaysugest) 
{ 
    
    sender=window.event||sender; 
    var srcElement=sender.srcElement||sender.target; 
    if(srcElement.tagName=="HTML"||srcElement.tagName=="BODY") 
    { 
        search_suggest.style.display='none'; 
    } 
    
}
*/

 
 
