<?php
			require_once("YinXiangMaLib.php");
			?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=GBK">
        <title></title>
    </head>
    <body>
	<form action="VerifyLogin.php" method="post">
        <?php
			echo YinXiangMa_GetYinXiangMaWidget();
        ?>
		<input type="submit" value="Yes" name="submit" />
	</form>
    </body>
</html>
