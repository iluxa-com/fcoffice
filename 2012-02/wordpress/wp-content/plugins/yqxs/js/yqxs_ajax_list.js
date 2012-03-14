$(document).ready(function(){
    var multi_thread=false;
    var insert_count=0;
    $('input[name=multi_thread]').toggle(
        function() {multi_thread=true;$(this).val('当前多线程状态: 开启')},
        function() {muti_thread = false; $(this).val('当前多线程状态: 关闭');}
    );
    
    //alert(single_url); //单篇采集页地址
    $('input[name=list_caiji]').click(
        function() {
            //采集开始后线程选择不可用
            $('input[name=multi_thread]').removeClass('button-primary').addClass('muti_thread_stop ').unbind('click').click(
                function() {alert('采集开始后无法再更改线程状态');}
             );
             $(this).val('正在采集...').removeClass('button-primary').addClass('muti_thread_stop ').unbind('click');
            $('.yqxs_load_str').empty();
            var $nounce = $("form").serialize() ;
            var  p=0;
            var count = $('.yqxs_list_item').length;
            $('.yqxs_list_item').each(
                function() {
                    //$sdata +='list_id='+
                    var  url = $(this).attr('rel');
                    var list_id = $(this).get(0).id;
                    var $sdata = 'yqxs0=1'+ '&'+'list_id='+ list_id + '&' +'url=' + url +'&'+$nounce;
                    
                    
                    $.ajax({
                          url: '/',
                          cache: false,
                          //timeout:15000,//这不是单个的，是控制全部的总请求时间
                          global:false,
                          async:multi_thread,
                          data:$sdata,
                          dataType:'json',
                          beforeSend:function(xhr) {
                            $("#"+list_id).append("<span class='yqxs_load_str' id='load_"+list_id+"' style=' margin-left:20px;color:blue'>Loading</span>");
                          },
                          complete: function() {
                                $("#load_"+list_id).remove();
                                p++;
                                if(p==count) {
                                    alert('完成');
                                    $('input[name=list_caiji]').val('共入库'+insert_count+'篇新小说').css({'color':'blue'});
                                    //采集自动化,网址id号自增
                                    //location.href= 'http://www.yqxscaiji.tk/wp-admin/admin.php?page=yqxs/function.php&yqxs_list_url=http://www.yqxs.com/data/writer/writer75.html';
                                }
                           },
                          success: function(data,textStatus){
                            if(data.error<0) {
                                $("#"+list_id).append("<span class='yqxs_load_str' style=' margin-left:20px;color:red;font-style :italic'>"+data.mess+"<a href="+data.permalink+" target='_blank'>查看</a>"+"</span>");
                            }else {
                                $("#"+list_id).append("<span class='yqxs_load_str' style=' margin-left:20px;color:green;font-style :italic'>"+data.mess+"</span>");
                                insert_count++;
                            }


                            },
                            error : function(xhr,textStatus,errorThrown) {
                                console.log(this);
                                if(null != textStatus) {
                                    var err = textStatus;
                                }else {
                                    var err = 'Error'
                                }
                                $("#"+list_id).append("<span class='yqxs_load_str' style=' font-style:italic;margin:10px;20px;color:red'>"+err+"</span>").append('<a href="'+single_url+'&yqxs_url=' +url+'" target="_blank">点击使用单篇采集-></a>');
                                
                                
                            }
                            
                    });
                   
                }
            );
        }
        
    );
    
    //5秒后自动点击按钮以采集自动化
     setTimeout(function() {$('input[name=list_caiji]').trigger('click');},5000);
  
}
);
     
 get_list_json = function () {
    $(".sender").each(function(i){
                //console.log(this.style.display='block');
                //console.log($(this).attr('rel'));
                $_this= $(this);
                console.log($_this);
                $.ajax({
                  url: $_this.attr('rel'),
                  cache: false,
                  async:false,
                  dataType:'json',
                  success: function(data){
                    //alert(data.value);
                    $_this.show();
                    i++;
                    if(i==$(".sender").length) {
                        $('#finish_mes').show();
                    }
                  }
                });
            });

 
 }       