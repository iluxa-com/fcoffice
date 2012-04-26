$.fn.muti_selector = function(hidden_name) {
    var $textInput = $(this);
    var textInput = $textInput.get(0);
    var muti_div_id = textInput.id +'_'+'muti_div';
    var $muti_div_id = $('#'+muti_div_id);
    var $groups = $muti_div_id.find('.groups');
    $(this).click(
        function(){
           
            var selectIndexes=$('input[name="'+hidden_name+'"]').val().split(',');
            if(selectIndexes[0]!='') {
                for(x=0;x<$groups.length;x++) {
                    if(in_array($groups.eq(x).val() , selectIndexes)){
                        $groups.eq(x).attr('checked','checked');
                    } else {
                        $groups.eq(x).removeAttr('checked');    
                    //console.log($('.groups').eq(x).val());
                    }
                    
                }
            }
            var offset = $(this).offset();
            $muti_div_id.css({
                'top':offset.top+$(this).outerHeight(true) ,
                'left':offset.left
            }).show('fast');
        //alert('hello');
        //console.log(textInput.id);
          
        });
       
    //确定选择
    $('#'+ muti_div_id +' .controller_items_ok').click(
        function(event){
            // console.log('ok');
            if(event.target==this) {
                var names='';
                var values='';
                //var $groups = $('#'+ muti_div_id + ' .groups');
                for(i=0;i<$groups.length;i++){
                    if($groups.eq(i).attr('checked')==undefined) continue;
                    names += $.trim($groups[i].nextSibling.nodeValue) +',';
                    values +=$groups.eq(i).val()+',';
                }
                if(names!=''){
                    names=names.substring(0,names.length-1);
                    values=values.substring(0,values.length-1);
                }
                //$('#members_display').val(names);
                $textInput.val(names);
                $('input[name="'+hidden_name+'"]').val(values);
                $muti_div_id.hide(); 
                 event.stopPropagation();
            //var selectIndexes=$('input[name="'+hidden_name+'"]').val();
            //console.log('选择的id:',selectIndexes);
          }  
        }
    );
    //取消按钮,仅隐藏当前的选项层
    $('#'+ muti_div_id +' .controller_items_cancel').click(
        function(event){
             //console.log('ok');
            if(event.target==this) {
                $muti_div_id.hide('fast'); 
                event.stopPropagation();
          }  
        }
    );
    
    
    
    
    //关闭弹出层
    
    $('html').click(
        function(event){
            
            if(event.target.id !=textInput.id && event.target.className !='groups' && event.target.className !='muti_div_testarea' && event.target.id !=muti_div_id && event.target.className !='controller_items_ok' && event.target.className !='muti_selector_controller') {
                //console.log(selectIndexes);
                $muti_div_id.hide();
            }
            
        //if(event.target != this) $('#muti_selector_float_div').hide();
        }
        );
    $('#'+ muti_div_id+' label').click(
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


