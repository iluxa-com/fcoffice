<?php
require_once("YinXiangMaLib.php");

$YinXiangMa_response = new YinXiangMaResponse();
$YinXiangMa_response=YinXiangMa_validRequest($_POST[YinXiangMa_response],$_POST[YinXiangMa_challenge]);
if($YinXiangMa_response->is_valid == "true")
{
	echo "Yes!\n";
}
else
{
	echo "No!";
    echo $YinXiangMa_response->error;
}
?>
