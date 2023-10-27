<?php require_once('Connections/hos.php'); ?>
<?php 

		mysql_select_db($database_hos, $hos);
		$update = "update ".$database_kohrx.".kohrx_queue_caller_list set not_response = 'Y' where hn='".$_GET['hn']."' and room_id='".$_GET['room_id']."'";
		$query_update = mysql_query($update, $hos) or die(mysql_error());
		echo "<script>window.location.reload();</script>"
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
</body>
</html>