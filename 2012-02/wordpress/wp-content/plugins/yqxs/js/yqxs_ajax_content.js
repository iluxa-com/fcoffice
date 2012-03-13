$(document).ready(function(){

    $(".ch_item").hide();
    if($('#ch_list').length >0) {
            setTimeout(get_content_json,100);
    }
}
);

get_content_json = function() {
           var i=0;
           $(".ch_item").each(function(){
                //console.log(this.style.display='block');
                //console.log($(this).attr('rel'));
                $_this= $(this);
                //console.log($_this);
                
                $.getJSON( '/',{'yqxs':'1','url':$_this.attr('rel'),'id':$_this.attr('title')},function(data){
                    console.log(data);
                    //alert(data.time);
                    $('#no_'+data.id).show().html($('#no_'+data.id).text() + ':' +data.url).appendTo($('#ch_list'));
                    
                    //console.log($_this);
                    i++;
                    if(i==$(".ch_item").length) {
                        $('#finish_mes').show().appendTo($('#ch_list'));
                    }
                });
            });
       }
  