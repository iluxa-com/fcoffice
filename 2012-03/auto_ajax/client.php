<html>
<head>
<style>
#finish_mes {
    display:none;
}
</style>
<script type="text/javascript" src="jquery-1.6.2.min.js"></script>
<script>

$(document).ready(function(){
    var  i = 0;
    $(".sender").hide();
    setTimeout(
      
        get_json1,100
      
        
    );
   
});


get_json1 = function() {
           $(".sender").each(function(i){
                //console.log(this.style.display='block');
                //console.log($(this).attr('rel'));
                $_this= $(this);
                //console.log($_this);
                $.getJSON($_this.attr('rel'),function(data){
                    //alert(data.time);
                    $('#no_'+data.id).show().html($('#no_'+data.id).text() + ':' +data.time);
                    
                    //console.log($_this);
                    i++;
                    if(i==$(".sender").length) {
                        $('#finish_mes').show();
                    }
                });
            });
       }
 
 get_json2 = function () {
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

</script>

<title> 连续多个ajax请求测试</title>

</head>
<body>
<?php
for($i=1;$i<=50;$i++) {
    $rand = microtime();
    echo "<div class='sender' id='no_{$i}' rel='http://test.com/auto_ajax/server.php?id={$i}&rand={$rand}' >第{$i}个</div>\n";
}
    
?>
<div id='finish_mes'><a href = '#'>完成</a></div>
</body>
</html>