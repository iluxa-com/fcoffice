var page=0;
$(window).scroll(function() {
	var o = $('#ADcon0');        
	if(o!=null ){              
	  var hght= document.body.scrollHeight;	 
	  var clt =document.documentElement.clientHeight;	
	  var top= document.documentElement.scrollTop ;	
	  if(top>=(parseInt(hght)-clt)&&page<5){		
		 page=parseInt(page)+1;	
		 ReadMore(page);
	  }
	}
  });

  function ReadMore(page){
       $("#news-bottom-list").html('正在获取信息...');
	   $("#news-bottom-list").append('<img src="/images/loading.gif">');	
		next=parseInt(page)+1;		
		html=$.get('/newread.php','page='+page,function (data){				
			$("#ADcon0").append(data);
			if(next<=5){
			$("#news-bottom-list").html("<a href='javascript:void(0);' onclick='ReadMore("+next+");'>查看更多资讯↓</a>");
			}
			else{
			$("#news-bottom-list").html('<span class="">上一页</span>[<strong><span class="">1</span></strong>][<a  href="#1"  onclick="javascript:process(\'GET\',\'storydata.php?pageID=2\',\'active\')">2</a>][<a  href="#1"  onclick="javascript:process(\'GET\',\'storydata.php?pageID=3\',\'active\')">3</a>][<a  href="#1"  onclick="javascript:process(\'GET\',\'storydata.php?pageID=4\',\'active\')">4</a>][<a  href="#1"  onclick="javascript:process(\'GET\',\'storydata.php?pageID=5\',\'active\')">5</a>][<a  href="#1"  onclick="javascript:process(\'GET\',\'storydata.php?pageID=6\',\'active\')">6</a>][<a  href="#1"  onclick="javascript:process(\'GET\',\'storydata.php?pageID=7\',\'active\')">7</a>][<a  href="#1"  onclick="javascript:process(\'GET\',\'storydata.php?pageID=8\',\'active\')">8</a>][<a  href="#1"  onclick="javascript:process(\'GET\',\'storydata.php?pageID=9\',\'active\')">9</a>][<a  href="#1"  onclick="javascript:process(\'GET\',\'storydata.php?pageID=10\',\'active\')">10</a>]<a  href="#1"  onclick="javascript:process(\'GET\',\'storydata.php?pageID=2\',\'active\')">下一页</a>');
			}
		
		});			
}