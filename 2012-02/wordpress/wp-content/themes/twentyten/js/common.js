/*
function $(objID) {
    return document.getElementById(objID)
};
*/

 $(document).ready(
    function(){
    //滚动公告
    $("#scroll_div").Scroll({line:1,speed:500,timer:3000,up:"but_up",down:"but_down"});
    
    //点击搜索栏外的隐藏结果，替代原有报错的代码
    $('body').click(
        function(event) {
            event.stopPropagation(); 
            if(event.target!=document.getElementById('searchword')){
                $('#search_suggest').hide();
            }
        }
    );
    //懒加载
     $("img").lazyload({
        effect       : "fadeIn"
    });
    //修复小说单页章节列表的下划虚线最后章节不满行的问题
    var ch_count = $('.tb .yqxs_ch_item').length;
    if(ch_count%3!=0){
        var fix_width = (4-ch_count%3) * 210;
       // console.log(fix_width);
        $('.tb .yqxs_ch_item').eq(ch_count-1).css('width',fix_width);
    }
    
    //历史记录
    $('#read_history').click(
        function (e) {
            //定位            
            $(this).css('color','#ff0');
            var offset = $(this).offset();
            $('#yqxs_filter').css({
                'top':offset.top+$(this).outerHeight(true) ,
                'left':offset.left
            }).show();
            $('#yqxs_history').css({
                
                'z-index':100,
                'top':offset.top+$(this).outerHeight(true) ,
                'left':offset.left
            }).show();
          // console.log($(this).offset());
            return false;                
        }
    );
    
    $('body').click(
        function(e){
            
            if( $(e.target) !=$('#yqxs_history *')){
                $('#yqxs_history').hide();
                $('#yqxs_filter').hide();
                $('#read_history').css('color','#fff');
            }
            e.stopPropagation();
            
        }
    )
    

  
} );
  


/* -------------------原代码------------------------*/
function openWindow(url, w, h) {
    var iTop = (window.screen.availHeight - 30 - h) / 2;
    var iLeft = (window.screen.availWidth - 10 - w) / 2;
    window.open(url, '', 'height=' + h + ',,innerHeight=' + h + ',width=' + w + ',innerWidth=' + w + ',top=' + iTop + ',left=' + iLeft + ',toolbar=no,menubar=no,scrollbars=no,resizeable=no,location=no,status=no');
    return false
}
function changeTab(a, b) {
    var l = a - 1;
    var o = document.getElementById('tabList').getElementsByTagName('li');
    var p = document.getElementById('comicList').getElementsByTagName('ul');
    for (var i = 0; i < a; i++) {
        p[i].style.display = 'none';
        o[i].className = (i == l) ? 'end': ''
    }
    o[b].className = (l == b) ? 'end on': 'on';
    p[b].style.display = 'block'
}
function getRequest() {
    var args = new Object();
    var query = location.search.substring(1);
    var pairs = query.split("&");
    for (var i = 0; i < pairs.length; i++) {
        var pos = pairs[i].indexOf('=');
        if (pos == -1) continue;
        var argname = pairs[i].substring(0, pos);
        var value = pairs[i].substring(pos + 1);
        args[argname] = unescape(value)
    }
    return args
}



