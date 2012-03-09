$(document).ready(function(){
   /*管理页的js*/
  var  if_cj =false;
  $('#info-form').submit(
    function () {
        var  ajax_post_title = $('input[name=post_title]').val();
        var  send = {'ajax_post_title':ajax_post_title};
        $.post(
            '/',send,function(id) {
                console.log(id);
                if(id>0) {
                     if(confirm('文章已存在，是否继续采集？')) {
                            $("#info-form").append("<input name='ID' type='hidden' value='" + id +"'>");
                            if_cj = true;
                            $('#info-form').submit();
                     }
                     
                }else {
                    if_cj = true;
                }
                
            },'text'
        );
       return if_cj;
    });
  
  
});

