//不同于单篇的，这是单独控制的章节入库

$(document).ready(function(){
    
    $(".ch_item").hide();
    if($('#ch_list2').length >0) {
            setTimeout(get_content_json_s,1000);
    }
}
);

get_content_json_s = function() {
            var i=0;
           var $ch_length = $(".ch_item").length;
            $('.yqxs_header').html('正在入库...').append('<b id="counter">('+i+'/'+$ch_length +')</b>');;
            $('.yqxs_loading').show().appendTo($('.yqxs_header')) ;
           
           $(".ch_item").each(function(){
                $_this= $(this);
                $.getJSON( '/',{'yqxs':'1','url':$_this.attr('rel'),'id':$_this.attr('title')},function(data){
                    console.log(data);
                    $('#no_'+data.id).show().html($('#no_'+data.id).text() + ':' +data.url).appendTo($('#ch_list2'));
                    //console.log($_this);
                    i++;
                    $('#counter').text('('+i+'/'+$ch_length +')');
                    
                    if(i==$ch_length) {
                  
                        $('.yqxs_header').text('仍在继续, 正在跳转...');
                        //2秒后自动跳转
                        setTimeout(function(){location.href=document.location.href},2000);
                    }
                });
            });
       }
  