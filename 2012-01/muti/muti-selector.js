$.fn.muti_selector = function(hidden_name) {
    var $textInput = $(this);
    var textInput = $textInput.get(0);
    $(this).click(
        function(){
            var selectIndexes=$('input[name="'+hidden_name+'"]').val().split(',');
            if(selectIndexes[0]!='') {
                for(x=0;x<$('.groups').length;x++) {
                    if(in_array($('.groups').eq(x).val() , selectIndexes)){
                        $('.groups').eq(x).attr('checked','checked');
                    } else {
                        $('.groups').eq(x).removeAttr('checked');    
                    //console.log($('.groups').eq(x).val());
                    }
                    
                }
            }
            var offset = $(this).offset();
            $('#muti_selector_float_div').css({
                'top':offset.top+$(this).outerHeight(true) ,
                'left':offset.left
            }).show();
        //alert('hello');
        //console.log(textInput.id);
          
        });
       
    //确定选择
    $('#muti_selector_float_div #ok').click(
        function(event){
            // console.log('test');
            if(event.target.id != 'ok') exit();
            var names='';
            var values='';
            for(i=0;i<$('#muti_selector_float_div .groups').length;i++){
                if($('#muti_selector_float_div .groups').eq(i).attr('checked')==undefined) continue;
                names += $.trim($('#muti_selector_float_div .groups')[i].nextSibling.nodeValue) +',';
                values +=$('#muti_selector_float_div .groups').eq(i).val()+',';
            }
            if(names!=''){
                names=names.substring(0,names.length-1);
                values=values.substring(0,values.length-1);
            }
            //$('#members_display').val(names);
            $textInput.val(names);
            $('input[name="'+hidden_name+'"]').val(values);
            $('#muti_selector_float_div').hide();   
        //var selectIndexes=$('input[name="'+hidden_name+'"]').val();
        //console.log('选择的id:',selectIndexes);
            
        }
        );
        
    //关闭弹出层
    
    $('html').click(
        function(event){
            
            if(event.target.id !=textInput.id && event.target.className !='groups' && event.target.id !='muti_selector_float_div' && event.target.id !='ok' && event.target.id !='muti_selector_controller') {
                //console.log(selectIndexes);
                $('#muti_selector_float_div').hide();
            }
            
        //if(event.target != this) $('#muti_selector_float_div').hide();
        }
        );
    $('#muti_selector_float_div label').click(
        function(event) {
             event.stopPropagation();
        }
        );
       
}
    
function in_array(v,a) {
    var i;
    for(i=0;i<a.length;i++){
        if(v==a[i]){
            return true;
        }
    }
    return false;
}


