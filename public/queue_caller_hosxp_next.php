<?php require_once('Connections/hos.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$get_ip=$_SERVER["REMOTE_ADDR"]; 

/// ค้นหาข้อมูลเครื่อง //////////
	mysql_select_db($database_hos, $hos);
$query_rs_search = "select * from ".$database_kohrx.".kohrx_queue_caller_channel where ip='".$get_ip."'";
$rs_search = mysql_query($query_rs_search, $hos) or die(mysql_error());
$row_rs_search = mysql_fetch_assoc($rs_search);
$totalRows_rs_search = mysql_num_rows($rs_search);

mysql_select_db($database_hos, $hos);
$query_rs_q_config = "select * from ".$database_kohrx.".kohrx_hosxp_queue_caller_config where depcode='".$row_rs_search['depcode']."' and substr(last_queue_datetime,1,10)=CURDATE()";
$rs_q_config = mysql_query($query_rs_q_config, $hos) or die(mysql_error());
$row_rs_q_config = mysql_fetch_assoc($rs_q_config);
$totalRows_rs_q_config = mysql_num_rows($rs_q_config);


?>
<?php
if(isset($_GET['action'])){
if($_GET['action']=="next"){
	if($row_rs_q_config['first_queue']<$row_rs_q_config['last_queue']){
		if($row_rs_search['q_dep_type']=="main_dep"){
		mysql_select_db($database_hos, $hos);
		$query_rs_q_last = "select main_dep_queue from ovst where main_dep='".$row_rs_search['depcode']."' and vstdate=CURDATE() and main_dep_queue > '".$row_rs_q_config['last_queue']."' order by main_dep_queue ASC limit 1";
		$rs_q_last = mysql_query($query_rs_q_last, $hos) or die(mysql_error());
		$row_rs_q_last = mysql_fetch_assoc($rs_q_last);
		$totalRows_rs_q_last = mysql_num_rows($rs_q_last);

			$next_queue=$row_rs_q_last['main_dep_queue'];

		mysql_free_result($rs_q_last);		

		}
		else if($row_rs_search['q_dep_type']=="cur_dep"){
		mysql_select_db($database_hos, $hos);
		$query_rs_q_last = "select main_dep_queue,cur_dep_time from ovst where cur_dep='".$row_rs_search['depcode']."' and vstdate=CURDATE() and cur_dep_time > '".$row_rs_q_config['last_cur_dep_time']."' order by cur_dep_time ASC limit 1";
		$rs_q_last = mysql_query($query_rs_q_last, $hos) or die(mysql_error());
		$row_rs_q_last = mysql_fetch_assoc($rs_q_last);
		$totalRows_rs_q_last = mysql_num_rows($rs_q_last);

			$next_queue=$row_rs_q_last['main_dep_queue'];
			$next_time=$row_rs_q_last['cur_dep_time'];

		mysql_free_result($rs_q_last);		
		}
}
else if($row_rs_q_config['first_queue']>=$row_rs_q_config['last_queue']){
		if($row_rs_search['q_dep_type']=="main_dep"){
		mysql_select_db($database_hos, $hos);
		$query_rs_q_last = "select main_dep_queue from ovst where main_dep='".$row_rs_search['depcode']."' and vstdate=CURDATE() and main_dep_queue > '".$row_rs_q_config['first_queue']."' order by main_dep_queue ASC limit 1";
		$rs_q_last = mysql_query($query_rs_q_last, $hos) or die(mysql_error());
		$row_rs_q_last = mysql_fetch_assoc($rs_q_last);
		$totalRows_rs_q_last = mysql_num_rows($rs_q_last);

			$next_queue=$row_rs_q_last['main_dep_queue'];

		mysql_free_result($rs_q_last);		
		
		}
		else if($row_rs_search['q_dep_type']=="cur_dep"){
		mysql_select_db($database_hos, $hos);
		$query_rs_q_last = "select main_dep_queue,cur_dep_time from ovst where cur_dep='".$row_rs_search['depcode']."' and vstdate=CURDATE() and cur_dep_time > '".$row_rs_q_config['first_cur_dep_time']."' order by cur_dep_time ASC limit 1";
		$rs_q_last = mysql_query($query_rs_q_last, $hos) or die(mysql_error());
		$row_rs_q_last = mysql_fetch_assoc($rs_q_last);
		$totalRows_rs_q_last = mysql_num_rows($rs_q_last);

			$next_queue=$row_rs_q_last['main_dep_queue'];
			$next_time=$row_rs_q_last['cur_dep_time'];
		mysql_free_result($rs_q_last);		
		
		}
	}


}
if($_GET['action']=="previous"){
	if($row_rs_q_config['first_queue']<$row_rs_q_config['last_queue']){
				if($row_rs_search['q_dep_type']=="main_dep"){
				mysql_select_db($database_hos, $hos);
				$query_rs_q_last = "select main_dep_queue from ovst where main_dep='".$row_rs_search['depcode']."' and vstdate=CURDATE() and main_dep_queue < '".$row_rs_q_config['last_queue']."' order by main_dep_queue DESC limit 1";
				$rs_q_last = mysql_query($query_rs_q_last, $hos) or die(mysql_error());
				$row_rs_q_last = mysql_fetch_assoc($rs_q_last);
				$totalRows_rs_q_last = mysql_num_rows($rs_q_last);
		
					$next_queue=$row_rs_q_last['main_dep_queue'];
		
				mysql_free_result($rs_q_last);		
				}
				else if($row_rs_search['q_dep_type']=="cur_dep"){
				mysql_select_db($database_hos, $hos);
				$query_rs_q_last = "select main_dep_queue,cur_dep_time from ovst where cur_dep='".$row_rs_search['depcode']."' and vstdate=CURDATE() and cur_dep_time < '".$row_rs_q_config['last_cur_dep_time']."' order by cur_dep_time DESC limit 1";
				$rs_q_last = mysql_query($query_rs_q_last, $hos) or die(mysql_error());
				$row_rs_q_last = mysql_fetch_assoc($rs_q_last);
				$totalRows_rs_q_last = mysql_num_rows($rs_q_last);
		
					$next_queue=$row_rs_q_last['main_dep_queue'];
					$next_time=$row_rs_q_last['cur_dep_time'];

		
				mysql_free_result($rs_q_last);		
				
				}
				
}	
else if($row_rs_q_config['first_queue']>=$row_rs_q_config['last_queue']){
	if($row_rs_search['q_dep_type']=="main_dep"){

		if($row_rs_q_config['first_queue']>1){
				mysql_select_db($database_hos, $hos);
				$query_rs_q_last = "select main_dep_queue from ovst where main_dep='".$row_rs_search['depcode']."' and vstdate=CURDATE() and main_dep_queue < '".$row_rs_q_config['first_queue']."' order by main_dep_queue DESC limit 1";
				$rs_q_last = mysql_query($query_rs_q_last, $hos) or die(mysql_error());
				$row_rs_q_last = mysql_fetch_assoc($rs_q_last);
				$totalRows_rs_q_last = mysql_num_rows($rs_q_last);
		
					$next_queue=$row_rs_q_last['main_dep_queue'];
		
				mysql_free_result($rs_q_last);		
		}
		else 
			if($row_rs_q_config['first_queue']==1){
			$next_queue=1;
			}
		}
	else if($row_rs_search['q_dep_type']=="cur_dep"){
		if($row_rs_q_config['first_queue']>1){
				mysql_select_db($database_hos, $hos);
				$query_rs_q_last = "select main_dep_queue,cur_dep_time from ovst where cur_dep='".$row_rs_search['depcode']."' and vstdate=CURDATE() and cur_dep_time < '".$row_rs_q_config['first_cur_dep_time']."' order by cur_dep_time DESC limit 1";
				$rs_q_last = mysql_query($query_rs_q_last, $hos) or die(mysql_error());
				$row_rs_q_last = mysql_fetch_assoc($rs_q_last);
				$totalRows_rs_q_last = mysql_num_rows($rs_q_last);
		
					$next_queue=$row_rs_q_last['main_dep_queue'];
					$next_time=$row_rs_q_last['cur_dep_time'];
		
				mysql_free_result($rs_q_last);		
		
		}
		else{
				mysql_select_db($database_hos, $hos);
				$query_rs_q_last = "select main_dep_queue,cur_dep_time from ovst where cur_dep='".$row_rs_search['depcode']."' and vstdate=CURDATE() and main_dep_queue = 1 order by cur_dep_time DESC limit 1";
				$rs_q_last = mysql_query($query_rs_q_last, $hos) or die(mysql_error());
				$row_rs_q_last = mysql_fetch_assoc($rs_q_last);
				$totalRows_rs_q_last = mysql_num_rows($rs_q_last);
		
					$next_queue=$row_rs_q_last['main_dep_queue'];
					$next_time=$row_rs_q_last['cur_dep_time'];
		
				mysql_free_result($rs_q_last);		
			
		}
	}
}
	
	}
	
if($_GET['action']=="recent"){
	if($row_rs_q_config['first_queue']<$row_rs_q_config['last_queue']){
	$next_queue=($row_rs_q_config['last_queue']);
	}
	else if($row_rs_q_config['first_queue']>=$row_rs_q_config['last_queue']){
	$next_queue=$row_rs_q_config['first_queue'];
	}
}

if($_GET['action']=="manual"){
	
	$next_queue=$_GET['input_q'];
	
	if($next_queue==0){
		echo "<script>alert('กรุณาเลือกคิวที่ไม่เท่ากับ 0');</script>";	
		exit();
	}
	
	mysql_select_db($database_hos, $hos);
	$query_rs_config = "select * from ".$database_kohrx.".kohrx_hosxp_queue_caller_config  where depcode='".$row_rs_search['depcode']."' ";
	$rs_config = mysql_query($query_rs_config, $hos) or die(mysql_error());
	$row_rs_config = mysql_fetch_assoc($rs_config);
	$totalRows_rs_config = mysql_num_rows($rs_config);	
	if($row_rs_config['first_queue']<$next_queue){
	mysql_select_db($database_hos, $hos);
	$query_rs_update = "update ".$database_kohrx.".kohrx_hosxp_queue_caller_config set first_queue=".$next_queue.",first_cur_dep_time='".$next_time."',last_queue_datetime=NOW() where depcode='".$row_rs_search['depcode']."'";
	$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());
		
	}
	
	
	mysql_free_result($rs_config);

	}





//ค้นหาชื่อผู้ป่วย
mysql_select_db($database_hos, $hos);
$query_rs_pt = "select p.hn,concat(p.pname,p.fname,' ',p.lname) as ptname from ovst o left outer join patient p on p.hn=o.hn where main_dep_queue='".$next_queue."' and ".$row_rs_search['q_dep_type']."='".$row_rs_search['depcode']."' and vstdate=CURDATE()";
$rs_pt = mysql_query($query_rs_pt, $hos) or die(mysql_error());
$row_rs_pt = mysql_fetch_assoc($rs_pt);
$totalRows_rs_pt = mysql_num_rows($rs_pt);

if($totalRows_rs_pt<>0){

if($row_rs_search['q_dep_type']=="main_dep"){			
		mysql_select_db($database_hos, $hos);
	$query_rs_insert = "insert into ".$database_kohrx.".kohrx_hosxp_queue_caller (queue_date_call,room_id,main_dep,main_dep_queue,channel_id) value (NOW(),'".$row_rs_search['room_id']."','".$row_rs_search['depcode']."',".$next_queue.",'".$row_rs_search['channel']."')";
	$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());

mysql_select_db($database_hos, $hos);
$query_rs_last = "select * from ".$database_kohrx.".kohrx_hosxp_queue_caller  where main_dep='".$row_rs_search['depcode']."' and substr(queue_date_call,1,10)=CURDATE() and room_id='".$row_rs_search['room_id']."' order by main_dep_queue DESC limit 1";
$rs_last = mysql_query($query_rs_last, $hos) or die(mysql_error());
$row_rs_last = mysql_fetch_assoc($rs_last);
$totalRows_rs_last = mysql_num_rows($rs_last);

$last_q=$row_rs_last['main_dep_queue'];
$last_time=$row_rs_last['cur_dep_time'];

mysql_free_result($rs_last);
	
	mysql_select_db($database_hos, $hos);
	$query_rs_update = "update ".$database_kohrx.".kohrx_hosxp_queue_caller_config set first_queue=".$last_q.",first_cur_dep_time='".$next_time."', last_queue=".$last_q.",last_cur_dep_time=NULL,last_queue_datetime=NOW() where depcode='".$row_rs_search['depcode']."'";
	$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());
	}
	if($row_rs_search['q_dep_type']=="cur_dep"){			
		mysql_select_db($database_hos, $hos);
	$query_rs_insert = "insert into ".$database_kohrx.".kohrx_hosxp_queue_caller (queue_date_call,room_id,main_dep,main_dep_queue,channel_id,cur_dep_time) value (NOW(),'".$row_rs_search['room_id']."','".$row_rs_search['depcode']."',".$next_queue.",'".$row_rs_search['channel']."','".$next_time."')";
	$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());

mysql_select_db($database_hos, $hos);
$query_rs_last = "select * from ".$database_kohrx.".kohrx_hosxp_queue_caller  where main_dep='".$row_rs_search['depcode']."' and substr(queue_date_call,1,10)=CURDATE() and room_id='".$row_rs_search['room_id']."' order by cur_dep_time DESC limit 1";
$rs_last = mysql_query($query_rs_last, $hos) or die(mysql_error());
$row_rs_last = mysql_fetch_assoc($rs_last);
$totalRows_rs_last = mysql_num_rows($rs_last);

$last_q=$row_rs_last['main_dep_queue'];
$last_time=$row_rs_last['cur_dep_time'];

mysql_free_result($rs_last);
	
	mysql_select_db($database_hos, $hos);
	$query_rs_update = "update ".$database_kohrx.".kohrx_hosxp_queue_caller_config set first_queue=".$last_q.",first_cur_dep_time='".$next_time."', last_queue=".$last_q.",last_cur_dep_time='".$last_time."',last_queue_datetime=NOW() where depcode='".$row_rs_search['depcode']."'";
	$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());
	}
	
	mysql_select_db($database_hos, $hos);
	$query_rs_insert = "insert into ".$database_kohrx.".kohrx_queue_caller_list (hn,patient_name,channel_id,room_id,patient_type,call_server,main_dep_queue,call_datetime,main_dep) value ('".$row_rs_pt['hn']."','".$row_rs_pt['ptname']."','".$row_rs_search['channel']."','".$row_rs_search['room_id']."','OPD','Y',".$next_queue.",NOW(),'".$row_rs_search['depcode']."')";
	$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());
}
else{
	echo "<script>config_q('".($next_queue-1)."');alert('ไม่มีคิวนี้ในระบบ');</script>";
	}

mysql_free_result($rs_pt);


	}
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
<?php
mysql_free_result($rs_search);

mysql_free_result($rs_q_config);


?>