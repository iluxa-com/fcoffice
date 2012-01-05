$.fn.muti_selector = function(hidden_name) {
    var $testArea = $(this);
    var testArea = $testArea.get(0);
    var muti_div_id = testArea.id +'_'+'muti_div';
    var $muti_div_id = $('#'+muti_div_id);
    var $muti_selector_groups = $muti_div_id.find('.muti_selector_groups');
    $(this).click(
        function(){
            var selectIndexes=$('input[name="'+hidden_name+'"]').val().split(',');
            if(selectIndexes[0]!='') {
                for(x=0;x<$muti_selector_groups.length;x++) {
                    if(in_array($muti_selector_groups.eq(x).val() , selectIndexes)){
                        $muti_selector_groups.eq(x).attr('checked','checked');
                    } else {
                        $muti_selector_groups.eq(x).removeAttr('checked');    
                    //console.log($('.muti_selector_groups').eq(x).val());
                    }
                    
                }
            }
            var offset = $(this).offset();
            $muti_div_id.css({
                'top':offset.top+$(this).outerHeight(true) ,
                'left':offset.left
            }).show();
          //event.stopPropagation();

        //console.log(testArea.id);
          
        }
        
        );
       
    //确定选择
    $('#'+ muti_div_id +' .controller_items_ok').click(
        function(event){
            // console.log('ok');
            if(event.target==this) {
                var names='';
                var values='';

                for(i=0;i<$muti_selector_groups.length;i++){
                    if($muti_selector_groups.eq(i).attr('checked')==undefined) continue;
                    names += $.trim($muti_selector_groups[i].nextSibling.nodeValue) +',';
                    values +=$muti_selector_groups.eq(i).val()+',';
                }
                if(names!=''){
                    names=names.substring(0,names.length-1);
                    values=values.substring(0,values.length-1);
                }
                //$('#members_display').val(names);
                $testArea.val(names);
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
                $muti_div_id.hide(); 
                event.stopPropagation();
          }  
        }
    );
    
    //关闭弹出层
    
    $('html').click(
        function(event){
            
            if(event.target.id !=testArea.id && event.target.className !='muti_selector_groups' && event.target.className !='muti_div_textarea' && event.target.id !=muti_div_id && event.target.className !='controller_items_ok' && event.target.className !='muti_selector_controller') {
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


