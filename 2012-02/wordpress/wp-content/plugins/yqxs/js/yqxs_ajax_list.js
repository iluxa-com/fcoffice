$(document).ready(function(){
    var multi_thread=false;
    $('input[name=multi_thread]').toggle(
        function() {multi_thread=true;$(this).val('当前多线程状态: 开启')},
        function() {muti_thread = false; $(this).val('当前多线程状态: 关闭');}
    );
    
    
    $('input[name=list_caiji]').click(
        function() {
            //采集开始后线程选择不可用
            $('input[name=multi_thread]').removeClass('button-primary').addClass('muti_thread_stop ').unbind('click').click(
                function() {alert('采集开始后无法再更改线程状态');}
             );
             $(this).val('正在采集...');
            $('.yqxs_load_str').empty();
            var $nounce = $("form").serialize() ;
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
                          success: function(data){
                            $("#load_"+list_id).remove();
                            $("#"+list_id).append("<span class='yqxs_load_str' style=' margin-left:20px;color:red'>OK</span>")
                          
                            //alert(data.value);
                            /*
                            $_this.show();
                            i++;
                            if(i==$(".sender").length) {
                                alert('finish');
                                //$('#finish_mes').show();
                            }
                            */
                            console.log(list_id);
                             //$("#"+list_id).
                            }
                    });
                   
                }
            );
        }
        
    );
    if($('#ch_list').length >0) {
            setTimeout(get_content_json,100);
    }
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