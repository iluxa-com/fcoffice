//不同于单篇的，这是单独控制的章节入库

$(document).ready(function(){
    
    $(".ch_item").hide();
    if($('#ch_list2').length >0) {
            setTimeout(get_content_json_ss,1000);
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
  
  get_content_json_ss = function() {
            var i=0;
           var $ch_length = $(".ch_item").length;
            $('.yqxs_header').html('正在入库...').append('<b id="counter">('+i+'/'+$ch_length +')</b>');;
            $('.yqxs_loading').show().appendTo($('.yqxs_header')) ;
           
           $(".ch_item").each(function(){
                $_this= $(this);
                $.ajax({
                          url: '/',
                          cache: false,
                          //timeout:15000,//这不是单个的，是控制全部的总请求时间
                          global:false,
                          async:true,
                          data:{'yqxs':'1','url':$_this.attr('rel'),'id':$_this.attr('title')},
                          dataType:'json',
                          yqid:$_this.attr('title'),
                          yqurl:$_this.attr('rel'),
                          beforeSend:function(xhr) {
                                  //$_this_dom= $_this;
                          },
                          complete: function() {

                            i++;

                            $('#counter').text('('+i+'/'+$ch_length +')');
                            //console.log(this.yqid);
                             $('#no_'+this.yqid).show();
                             //.html($('#no_'+this.yqid).text() + ':' +this.yqurl).appendTo($('#ch_list2'));
                            //console.log(this.data);
                            if(i==$ch_length) {
                                $('.yqxs_header').text('仍在继续, 正在跳转...');
                                //2秒后自动跳转
                                setTimeout(function(){location.href=document.location.href},2000);
                            }
                           },
                          success: function(data,textStatus){
                                console.log(data);
                                if(data==null) {
                                    $('#no_'+this.yqid).show().html($('#no_'+this.yqid).text() + ':' +this.yqurl).appendTo($('#ch_list2'));
                                }
                                $('#no_'+data.id).show().html($('#no_'+data.id).text() + ':' +data.url).appendTo($('#ch_list2'));
                                if(data.finish==true) {
                                          //console.log($('#a_'+data.id).length);
                                        if($('#a_'+data.post_id).length==0 && typeof data.post_id !=undefined){
                                            $('#no_'+data.id).css({'color':'red'}).append('<a id=a_' +data.post_id+ ' href="'+data.permalink +'">【查看发布页】</a>');
                                         }
                                }
                            },
                            error : function(xhr,textStatus,errorThrown) {
                               // $('#no_'+this.yqid).html('<b>数据出错: '+textStatus+'</b>');
                                $('#no_'+this.yqid).css({'color':'green'}).html($('#no_'+this.yqid).text() + ':' +this.yqurl).appendTo($('#ch_list2'));
                            }
                            
                    });
            });
       }