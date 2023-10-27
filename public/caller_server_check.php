<?php require_once('Connections/hos.php'); ?>
<?php $get_ip=$_SERVER["REMOTE_ADDR"]; 
	date_default_timezone_set('Asia/Bangkok');

mysql_select_db($database_hos, $hos);
$query_rs_time = "delete from ".$database_kohrx.".kohrx_queue_caller_server_check where ip=''";
$rs_time = mysql_query($query_rs_time, $hos) or die(mysql_error());

	mysql_select_db($database_hos, $hos);
	$query_rs_room = "select room_name,r.id from ".$database_kohrx.".kohrx_queue_caller_channel c left outer join ".$database_kohrx.".kohrx_queue_caller_room r on r.id=c.room_id where ip='".$get_ip."'";
	$rs_room = mysql_query($query_rs_room, $hos) or die(mysql_error());
	$row_rs_room = mysql_fetch_assoc($rs_room);
	$totalRows_rs_room = mysql_num_rows($rs_room);
	
mysql_select_db($database_hos, $hos);
$query_rs_roomcheck = "select * from ".$database_kohrx.".kohrx_queue_caller_server_check where room_id='".$row_rs_room['id']."'";
$rs_roomcheck = mysql_query($query_rs_roomcheck, $hos) or die(mysql_error());
$row_rs_roomcheck = mysql_fetch_assoc($rs_roomcheck);
$totalRows_rs_roomcheck = mysql_num_rows($rs_roomcheck);

if($totalRows_rs_roomcheck==0){
	mysql_select_db($database_hos, $hos);
	$query_update = "insert into ".$database_kohrx.".kohrx_queue_caller_server_check (room_id) value ('".$row_rs_room['id']."')";
	$rs_update = mysql_query($query_update, $hos) or die(mysql_error());	
	}

mysql_select_db($database_hos, $hos);
$query_rs_time = "update ".$database_kohrx.".kohrx_queue_caller_server_check set ip='".$get_ip."',time_update=NOW(),date_update=NOW() where room_id='".$row_rs_room['id']."'";
$rs_time = mysql_query($query_rs_time, $hos) or die(mysql_error());

mysql_free_result($rs_roomcheck);

mysql_free_result($rs_room);

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