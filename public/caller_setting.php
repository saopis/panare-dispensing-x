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
 function get_client_ip()
 {
      $ipaddress = '';
      if (getenv('HTTP_CLIENT_IP'))
          $ipaddress = getenv('HTTP_CLIENT_IP');
      else if(getenv('HTTP_X_FORWARDED_FOR'))
          $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
      else if(getenv('HTTP_X_FORWARDED'))
          $ipaddress = getenv('HTTP_X_FORWARDED');
      else if(getenv('HTTP_FORWARDED_FOR'))
          $ipaddress = getenv('HTTP_FORWARDED_FOR');
      else if(getenv('HTTP_FORWARDED'))
          $ipaddress = getenv('HTTP_FORWARDED');
      else if(getenv('REMOTE_ADDR'))
          $ipaddress = getenv('REMOTE_ADDR');
      else
          $ipaddress = 'UNKNOWN';

      return $ipaddress;
 }
 
 ///// GET IP /////////////////
$get_ip=$_SERVER["REMOTE_ADDR"];

include('include/function.php');

if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){
	if(isset($_POST['caller_tv'])&&($_POST['caller_tv']=="Y")){
			$caller_tv="'".$_POST['caller_tv']."'";
			$caller_tv2="\'".$_POST['caller_tv']."\'";
		}
	else {
			$caller_tv="NULL";
			$caller_tv2="NULL";
		}
	if(isset($_POST['patient_picture'])&&($_POST['patient_picture']=="Y")){
			$patient_picture="'".$_POST['patient_picture']."'";
			$patient_picture2="\'".$_POST['patient_picture']."\'";
		}
	else {
			$patient_picture="NULL";
			$patient_picture2="NULL";
		}
	if(isset($_POST['caller_default'])&&($_POST['caller_default']=="Y")&&($get_ip!="::1")){
			$caller_default=",caller_default='".$_POST['caller_default']."'";
			$caller_default2=",caller_default=\'".$_POST['caller_default']."\'";
			$caller_default3="'".$_POST['caller_default']."'";		
			$caller_default4="\'".$_POST['caller_default']."\'";		
		}
	else{
			$caller_default="";
			$caller_default2="";
			$caller_default3="NULL";		
			$caller_default4="NULL";				
	}
	/// ค้นหาข้อมูลเครื่อง //////////
	mysql_select_db($database_hos, $hos);
$query_rs_search = "select * from ".$database_kohrx.".kohrx_queue_caller_channel where ip='".$get_ip."'";
$rs_search = mysql_query($query_rs_search, $hos) or die(mysql_error());
$row_rs_search = mysql_fetch_assoc($rs_search);
$totalRows_rs_search = mysql_num_rows($rs_search);

	if($totalRows_rs_search==0){
	mysql_select_db($database_hos, $hos);
	$query_rs_insert = "insert into ".$database_kohrx.".kohrx_queue_caller_channel (ip,channel,room_id,call_server,caller_tv,caller_method,patient_picture,kskdepart,caller_default) value ('$get_ip','".$_POST['channel']."','".$_POST['room']."','Y',".$caller_tv.",'".$_POST['caller_method']."',".$patient_picture.",'".$_POST['kskdepart']."',".$caller_default3.")";
	$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_queue_caller_channel (ip,channel,room_id,call_server,caller_tv,caller_method,patient_picture,kskdepart,caller_default) value (\'".$get_ip."\',\'".$_POST['channel']."\',\'".$_POST['room']."\',\'Y\',".$caller_tv2.",\'".$_POST['caller_method']."\',".$patient_picture2.",\'".$_POST['kskdepart']."\',".$caller_default4.")')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

		}
		else {
		mysql_select_db($database_hos, $hos);
		$query_rs_search2 = "select ip from ".$database_kohrx.".kohrx_queue_caller_channel where room_id='".$_POST['room']."' and caller_default='Y'";
		//echo $query_rs_search2;
		echo console_log($query_rs_search2);	
		$rs_search2 = mysql_query($query_rs_search2, $hos) or die(mysql_error());
		$row_rs_search2 = mysql_fetch_assoc($rs_search2);
		$totalRows_rs_search2 = mysql_num_rows($rs_search2);
		
			$caller_ip=$row_rs_search2['ip'];
		
		mysql_free_result($rs_search2);
		
		if(($get_ip!=$caller_ip)&&($_POST['caller_default']=="Y")&&($get_ip!="::1")){	
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_queue_caller_channel set caller_default=NULL where room_id='".$_POST['room']."'";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

		mysql_select_db($database_hos, $hos);
		$query_rs_update = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_queue_caller_channel set caller_default=NULL where room_id=\'".$_POST['room']."\'')";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());
			
		}
			
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_queue_caller_channel set channel='".$_POST['channel']."',room_id='".$_POST['room']."',call_server='Y',caller_tv=".$caller_tv.",caller_method='".$_POST['caller_method']."',patient_picture=".$patient_picture.",kskdepart='".$_POST['kskdepart']."',queue_list=".$_POST['queue_list'].$caller_default." where ip='".$get_ip."'";
		//echo "<br><br>".$query_rs_update;
		
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_queue_caller_channel set channel=\'".$_POST['channel']."\',room_id=\'".$_POST['room']."\',call_server=\'Y\',caller_tv=".$caller_tv2.",caller_method=\'".$_POST['caller_method']."\',patient_picture=".$patient_picture2.",kskdepart=\'".$_POST['kskdepart']."\',queue_list=".$_POST['queue_list'].$caller_default2." where ip=\'".$get_ip."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());


			}
	
	echo "<script>parent.location.reload();</script>";
	
	}

if(isset($_POST['save2'])&&($_POST['save2']=="แก้ไข")){
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_queue_caller_room set room_name='".$_POST['room_name']."',print_server='".$_POST['printserver']."' where id='".$_POST['room_id']."'";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_queue_caller_room set room_name=\'".$_POST['room_name']."\',print_server=\'".$_POST['printserver']."\' where id=\'".$_POST['room_id']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	$success=1;
}
if(isset($_POST['save2'])&&($_POST['save2']=="บันทึก")){
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "insert into ".$database_kohrx.".kohrx_queue_caller_room (room_name,print_server) value ('".$_POST['room_name']."','".$_POST['printserver']."')";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_queue_caller_room (room_name,print_server) value (\'".$_POST['room_name']."\',\'".$_POST['printserver']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	$success=1;
}
if(isset($_GET['action'])&&($_GET['action']=="delete")){
	if(isset($_GET['room_id'])&&($_GET['room_id']!="")){
	mysql_select_db($database_hos, $hos);
	$query_rs_delete = "delete from ".$database_kohrx.".kohrx_queue_caller_room where id='".$_GET['room_id']."'";
		$rs_delete = mysql_query($query_rs_delete, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_queue_caller_room where id=\'".$_GET['room_id']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	$success=1;
	}
}
if(isset($_POST['save3'])&&($_POST['save3']=="แก้ไข")){
		if(isset($_POST['q_show'])&&($_POST['q_show']=="Y")){
			$q_show="'".$_POST['q_show']."'";	
			$q_show2="\'".$_POST['q_show']."\'";
		}
		else{
			$q_show="NULL";	
			$q_show2=$q_show;			
			}
		
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_queue_caller_channel_name set channel_name='".$_POST['channel_name']."' ,q_show=".$q_show." where id='".$_POST['channel_id']."'";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_queue_caller_channel_name set channel_name=\'".$_POST['channel_name']."\' ,q_show=".$q_show2." where id=\'".$_POST['channel_id']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	$success=1;

}
if(isset($_GET['action'])&&($_GET['action']=="delete")){
	if(isset($_GET['channel_id'])&&($_GET['channel_id']!="")){
	mysql_select_db($database_hos, $hos);
	$query_rs_delete = "delete from ".$database_kohrx.".kohrx_queue_caller_channel_name where id='".$_GET['channel_id']."'";
		$rs_delete = mysql_query($query_rs_delete, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_queue_caller_channel_name where id=\'".$_GET['channel_id']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	$success=1;
	}
}
if(isset($_POST['save3'])&&($_POST['save3']=="บันทึก")){
		if(isset($_POST['q_show'])&&($_POST['q_show']=="Y")){
			$q_show="'".$_POST['q_show']."'";	
			$q_show2="\'".$_POST['q_show']."\'";
		}
		else{
			$q_show="NULL";	
			$q_show2=$q_show;			
			}

		mysql_select_db($database_hos, $hos);
		$query_rs_update = "insert into ".$database_kohrx.".kohrx_queue_caller_channel_name (channel_name,q_show) value ('".$_POST['channel_name']."',".$q_show.")";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_queue_caller_channel_name (channel_name,q_show) value (\'".$_POST['channel_name']."\',".$q_show2.")')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	$success=1;
}
if($success==1){
	echo "<script>window.location='caller_setting.php';</script>";
	exit();
	}

mysql_select_db($database_hos, $hos);
$query_rs_channel = "select * from ".$database_kohrx.".kohrx_queue_caller_channel_name";
$rs_channel = mysql_query($query_rs_channel, $hos) or die(mysql_error());
$rs_channel2 = mysql_query($query_rs_channel, $hos) or die(mysql_error());
$row_rs_channel = mysql_fetch_assoc($rs_channel);
$totalRows_rs_channel = mysql_num_rows($rs_channel);

mysql_select_db($database_hos, $hos);
$query_rs_room = "select *  from ".$database_kohrx.".kohrx_queue_caller_room ";
$rs_room = mysql_query($query_rs_room, $hos) or die(mysql_error());
$rs_room2 = mysql_query($query_rs_room, $hos) or die(mysql_error());
$row_rs_room = mysql_fetch_assoc($rs_room);
$totalRows_rs_room = mysql_num_rows($rs_room);

/// ค้นหาข้อมูลเครื่อง //////////
mysql_select_db($database_hos, $hos);
$query_rs_iam = "select * from ".$database_kohrx.".kohrx_queue_caller_channel where ip='".$get_ip."'";
$rs_iam = mysql_query($query_rs_iam, $hos) or die(mysql_error());
$row_rs_iam = mysql_fetch_assoc($rs_iam);
$totalRows_rs_iam = mysql_num_rows($rs_iam);

if(isset($_GET['room_id'])&&($_GET['room_id']!="")){

mysql_select_db($database_hos, $hos);
$query_room_edit = "select * from ".$database_kohrx.".kohrx_queue_caller_room where id='".$_GET['room_id']."'";
$room_edit = mysql_query($query_room_edit, $hos) or die(mysql_error());
$row_room_edit = mysql_fetch_assoc($room_edit);
$totalRows_room_edit = mysql_num_rows($room_edit);
}
if(isset($_GET['channel_id'])&&($_GET['channel_id']!="")){

mysql_select_db($database_hos, $hos);
$query_channel_edit = "select * from ".$database_kohrx.".kohrx_queue_caller_channel_name where id='".$_GET['channel_id']."'";
$channel_edit = mysql_query($query_channel_edit, $hos) or die(mysql_error());
$row_channel_edit = mysql_fetch_assoc($channel_edit);
$totalRows_channel_edit = mysql_num_rows($channel_edit);

}
mysql_select_db($database_hos, $hos);
$query_rs_kskdepart = "select * from kskdepartment order by depcode";
$rs_kskdepart = mysql_query($query_rs_kskdepart, $hos) or die(mysql_error());
$row_rs_kskdepart = mysql_fetch_assoc($rs_kskdepart);
$totalRows_rs_kskdepart = mysql_num_rows($rs_kskdepart);

mysql_select_db($database_hos, $hos);
$query_rs_print = "select server_name from printserver";
$rs_print = mysql_query($query_rs_print, $hos) or die(mysql_error());
$row_rs_print = mysql_fetch_assoc($rs_print);
$totalRows_rs_print = mysql_num_rows($rs_print);
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php include('java_css_file.php'); ?>

<style>
* {
  box-sizing: border-box;
}
.column {
  float: left;
  padding: 10px;
}

.left {
  width: 40%;
}

.right {
  width: 59%;
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
.chat{padding:5px 20px 5px 10px}.chat .item{margin-bottom:10px}.chat .item:before,.chat .item:after{content:" ";display:table}.chat .item:after{clear:both}.chat .item>img{width:40px;height:40px;border:2px solid transparent;border-radius:50%}.chat .item>.online{border:2px solid #00a65a}.chat .item>.offline{border:2px solid #dd4b39}.chat .item>.message{margin-left:55px;margin-top:-40px}.chat .item>.message>.name{display:block;font-weight:600}.chat .item>.attachment{border-radius:3px;background:#f4f4f4;margin-left:65px;margin-right:15px;padding:10px}.chat .item>.attachment>h4{margin:0 0 5px 0;font-weight:600;font-size:14px}.chat .item>.attachment>p,.chat .item>.attachment>.filename{font-weight:600;font-size:13px;font-style:italic;margin:0}.chat .item>.attachment:before,.chat .item>.attachment:after{content:" ";display:table}.chat .item>.attachment:after{clear:both}.box-input{max-width:200px}
html,body{overflow-x:hidden;}
html,body{overflow-y:hidden; }
	::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    box-shadow: inset 0 0 10px 10px #E6E6E6;
    border: solid 3px transparent;
}

::-webkit-scrollbar-thumb {
    box-shadow: inset 0 0 10px 10px #CCCCCC;
    border: solid 3px transparent;
}
</style>
</head>

<body>
<nav class="navbar navbar-dark bg-info text-white fixed-top">
  <!-- Navbar content -->
    <span class="font18"><i class="fab fa-whmcs font20"></i>&ensp;ตั้งค่าการเรียกชื่อ</span>
</nav>
<div style="margin-top:40px;">
<form id="form1" name="form1" method="post" action="caller_setting.php" >
<div class="row">
  <div class="column left" style="padding:10px;" >
  <div class="container-fluid" align="center">
  <h3>ตั้งค่าเรียกชื่อผู้ป่วย</h3>
  <hr />
  	<div class="form-group row" align="left">
    	<label class="col-sm-3 col-form-label" for="ip">IP</label>
        <div class="col">
        <input type="ip" class="form-control" id="ip" value="<?php echo $get_ip ?>" readonly="readonly">
    	</div>
    </div>
  	<div class="form-group row" align="left">
    	<label class="col-sm-3 col-form-label font14" for="channel">ช่องบริการ&nbsp;<i class="fas fa-asterisk text-danger font12"></i></label>
        <div class="col">
        <select name="channel" id="channel" class="form-control form-control-sm">
        <option value="-" <?php if (!(strcmp("-", $row_rs_iam['channel']))) {echo "selected=\"selected\"";} ?>>-</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rs_channel['id']?>"<?php if (!(strcmp($row_rs_channel['id'], $row_rs_iam['channel']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_channel['channel_name']?></option>
        <?php
} while ($row_rs_channel = mysql_fetch_assoc($rs_channel));
  $rows = mysql_num_rows($rs_channel);
  if($rows > 0) {
      mysql_data_seek($rs_channel, 0);
	  $row_rs_channel = mysql_fetch_assoc($rs_channel);
  }
?>
      </select>
    	</div>
    </div>
  <div class="form-group row" align="left">
    	<label class="col-sm-3 col-form-label font14" for="room">ห้องที่เรียก&nbsp;<i class="fas fa-asterisk font12 text-danger"></i></label>
        <div class="col">
        <select name="room" id="room" class="form-control form-control-sm">
        <option value="-" <?php if (!(strcmp("-", $row_rs_iam['room_id']))) {echo "selected=\"selected\"";} ?>>-</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rs_room['id']?>"<?php if (!(strcmp($row_rs_room['id'], $row_rs_iam['room_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_room['room_name']?></option>
        <?php
} while ($row_rs_room = mysql_fetch_assoc($rs_room));
  $rows = mysql_num_rows($rs_room);
  if($rows > 0) {
      mysql_data_seek($rs_room, 0);
	  $row_rs_room = mysql_fetch_assoc($rs_room);
  }
?>
      </select>
      </div>
    </div>
  <div class="form-group row" align="left">
    	<label class="col-sm-3 col-form-label font14" for="caller_method">วิธีการเรียก&nbsp;<i class="fas fa-asterisk font12 text-danger"></i></label>
        <div class="col">
        <select name="caller_method" id="caller_method" class="form-control form-control-sm">
          <option value="1" <?php if (!(strcmp(1, $row_rs_iam['caller_method']))) {echo "selected=\"selected\"";} ?>>ชื่อผู้ป่วย</option>
          <option value="2" <?php if (!(strcmp(2, $row_rs_iam['caller_method']))) {echo "selected=\"selected\"";} ?>>คิว(เฉพาะระบบออกบัตรคิว)</option>
          <option value="3" <?php if (!(strcmp(3, $row_rs_iam['caller_method']))) {echo "selected=\"selected\"";} ?>>คิว+ชื่อ(เฉพาะระบบออกบัตรคิว)</option>
      </select>
    	</div>
  </div>
  <div class="form-group row" align="left">
  <label class="col-sm-3 col-form-label font14" for="kskdepart">แผนก(KSK)</label>
  <div class="col">
    <select name="kskdepart" id="kskdepart" class="form-control form-control-sm">
      <?php
do {  
?>
      <option value="<?php echo $row_rs_kskdepart['depcode']?>"<?php if (!(strcmp($row_rs_kskdepart['depcode'], $row_rs_iam['kskdepart']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_kskdepart['department']?></option>
      <?php
} while ($row_rs_kskdepart = mysql_fetch_assoc($rs_kskdepart));
  $rows = mysql_num_rows($rs_kskdepart);
  if($rows > 0) {
      mysql_data_seek($rs_kskdepart, 0);
	  $row_rs_kskdepart = mysql_fetch_assoc($rs_kskdepart);
  }
?>
    </select>
  </div>
</div>

  <div class="form-group row" align="left">
    	<label class="col-sm-3 col-form-label" for="queue_list">หน้าหลัก</label>
        <div class="col">
        <select name="queue_list" id="queue_list" class="form-control">
          <option value="1" <?php if (!(strcmp(1, $row_rs_iam['queue_list']))) {echo "selected=\"selected\"";} ?>>แสดงคิวส่งจากแผนกอื่น</option>
          <option value="2" <?php if (!(strcmp(2, $row_rs_iam['queue_list']))) {echo "selected=\"selected\"";} ?>>คิวจากระบบออกบัตรคิว</option>
      </select>
    	</div>
  </div>
<div class="custom-control custom-switch text-left" style="padding-left:100px;">  
  <input <?php if (!(strcmp($row_rs_iam['caller_default'],"Y"))) {echo "checked=\"checked\"";} ?> name="caller_default" type="checkbox" class="custom-control-input" id="caller_default" value="Y" />  
  <label class="custom-control-label text-primary" for="caller_default">ตั้งเป็นเครื่องเริ่มต้นของ Caller Server</label>
  </div>

<div class="custom-control custom-switch text-left" style="padding-left:100px;">  
  <input <?php if (!(strcmp($row_rs_iam['caller_tv'],"Y"))) {echo "checked=\"checked\"";} ?> name="caller_tv" class="custom-control-input" type="checkbox" id="caller_tv" value="Y" />
<label class="custom-control-label" for="caller_tv">เสียงออกทางหน้าจอทีวี</label>
</div>
<div class="custom-control custom-switch text-left" style="padding-left:100px;">  
  <input <?php if (!(strcmp($row_rs_iam['patient_picture'],"Y"))) {echo "checked=\"checked\"";} ?> name="patient_picture" type="checkbox" class="custom-control-input" id="patient_picture" value="Y" />  
  <label class="custom-control-label" for="patient_picture">แสดงรูปผู้ป่วยที่หน้าจอแสดงคิวห้องยา</label>
  </div>
 <div>
  <div class="col" align="center"><input type="submit" class="btn btn-primary" name="save" id="save" value="บันทึก" /></div>
</div>
  </div>
  </div>

<div class="column right" style="border-left: 1px #CCCCCC solid;overflow:scroll;overflow-x:hidden;overflow-y:auto; height:550px;">

  <div class="contrainer" >
  <h4 >ตั้งค่าห้องที่ใช้เรียก</h4>
          <hr/>

  <div class="form-group row" align="left"> 
    <label class="col-sm-auto col-form-label font12" for="room_name">ชื่อห้อง</label>
      <div class="col-sm-auto" align="left">
        <input type="text" class="form-control form-control-sm" id="room_name" name="room_name" value="<?php echo $row_room_edit['room_name']; ?>"/>
      </div>
        <label class="col-sm-auto col-form-label font12" for="room_name">print server</label>
      <div class="col-sm-auto" align="left">
		<select name="printserver" id="printserver" class="form-control form-control-sm" style="width:150px;">
		  <?php
do {  
?>
		  <option value="<?php echo $row_rs_print['server_name']?>"<?php if (!(strcmp($row_rs_print['server_name'], $row_room_edit['print_server']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_print['server_name']?></option>
		  <?php
} while ($row_rs_print = mysql_fetch_assoc($rs_print));
  $rows = mysql_num_rows($rs_print);
  if($rows > 0) {
      mysql_data_seek($rs_print, 0);
	  $row_rs_print = mysql_fetch_assoc($rs_print);
  }
?>
		</select>      
      </div>
      <div class="col-sm-auto" align="left"><input type="submit" class="btn btn-success" name="save2" id="save2" value="<?php if($totalRows_room_edit<>0){ echo "แก้ไข"; } else { echo "บันทึก"; } ?>" />
        <input name="room_id" type="hidden" id="room_id" value="<?php echo $_GET['room_id']; ?>" />
      </div>
  </div>
    <div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="table" class="table table-sm table-striped table-bordered" >
	<thead>
  <tr>
    <td align="center">room_id</td>
    <td align="center">ชื่อห้อง</td>
    <td align="center">Print Server</td>
    <td align="center">&nbsp;</td>
  </tr>
  </thead>
  <tbody>
	<?php  do {  if($row_rs_room2['room_name']!=""){  ?>
  <tr>
    <td align="center"><?php echo $row_rs_room2['id']; ?></td>
    <td align="center"><?php echo $row_rs_room2['room_name']; ?></td>
    <td align="center"><?php echo $row_rs_room2['print_server']; ?></td>
    <td align="center"><i class="fas fa-edit" style="color:#333; font-size:18px; cursor:pointer;" onclick="window.location='caller_setting.php?room_id=<?php echo $row_rs_room2['id']; ?>'"></i>&nbsp;<i class="fas fa-trash" style="color:#333; font-size:18px; cursor:pointer;" onclick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่')==true){window .location='caller_setting.php?action=delete&room_id=<?php echo $row_rs_room2['id']; ?>';} "></i></td>
  </tr>
  <?php
	}
	}while($row_rs_room2 = mysql_fetch_assoc($rs_room2)); ?>
  </tbody>
</table>
    </div>
  </div>
<!-- ช่องที่ใช้เรียก -->
  <div class="contrainer">
  <h4 style="padding-top:20px;">ตั้งค่าช่องบริการที่ใช้เรียก</h4>
  <hr/>
    <div class="form-group row" align="left"> 
        <label class="col-sm-auto col-form-label font14" for="room_name">ชื่อช่องบริการ</label>
      <div class="col-sm-auto" align="left">
        <input name="channel_name" type="text" class="form-control form-control-sm" id="channel_name" value="<?php echo $row_channel_edit['channel_name']; ?>"/>
      </div>
  <div class="col-sm-auto" align="left">
  <input <?php if (!(strcmp($row_channel_edit['q_show'],"Y"))) {echo "checked=\"checked\"";} ?> name="q_show"  id="q_show" type="checkbox" value="Y" />
    <label class="col-sm-auto col-form-label font12" for="q_show">เรียกคิวห้องจ่ายยา</label>
      <input type="submit" class="btn btn-success" name="save3" id="save3" value="<?php if($totalRows_channel_edit<>0){ echo "แก้ไข"; } else { echo "บันทึก"; } ?>" />
      <input name="channel_id" type="hidden" id="channel_id" value="<?php echo $_GET['channel_id']; ?>" />
 </div>
  </div>  

    </div>
    <div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="table2" class="table table-sm table-striped table-bordered" >
	<thead>
  <tr>
    <td align="center">ลำดับ</td>
    <td align="center">ชื่อช่องบริการ</td>
    <td align="center">เรียกคิวรับยา</td>
    <td align="center">&nbsp;</td>
  </tr>
  </thead>
  <tbody>
	<?php $i=0; do {  if($row_rs_channel2['channel_name']!=""){ $i++; ?>
  <tr>
    <td align="center"><?php echo $i; ?></td>
    <td align="center"><?php echo $row_rs_channel2['channel_name']; ?></td>
    <td align="center"><?php echo $row_rs_channel2['q_show']; ?></td>
    <td align="center"><i class="fas fa-edit" style="color:#333; font-size:18px; cursor:pointer;" onclick="window.location='caller_setting.php?channel_id=<?php echo $row_rs_channel2['id']; ?>'"></i>&nbsp;<i class="fas fa-trash" style="color:#333; font-size:18px; cursor:pointer;" onclick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่')==true){window.location='caller_setting.php?action=delete&channel_id=<?php echo $row_rs_channel2['id']; ?>'};"/></td>
  </tr>
  <?php
	}
	}while($row_rs_channel2 = mysql_fetch_assoc($rs_channel2)); ?>
  </tbody>
</table>
    </div>
  </div>
<!--//////////////////////////////////////////// -->

</div>
</form>
</div>
</body>
</html>
<?php
mysql_free_result($rs_channel);

mysql_free_result($rs_room);

mysql_free_result($rs_iam);

mysql_free_result($rs_kskdepart);

if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){

mysql_free_result($rs_search);
}
if(isset($_GET['room_id'])&&($_GET['room_id']!="")){
mysql_free_result($room_edit);
}
if(isset($_GET['channel_id'])&&($_GET['channel_id']!="")){
mysql_free_result($channel_edit);

mysql_free_result($rs_print);
}

?>
