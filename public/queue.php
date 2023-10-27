<?php require_once('Connections/hos.php'); ?>
<?php ob_start();?>
<?php session_start();?>
<?php
echo "asdf";
$prefix=$_GET['prefix'];
$second=$_GET['second'];
$channel_name=$_GET['channel_name'];
$subfix=$_GET['subfix'];
$channel=$_GET['channel'];
$patient_name=$_GET['patient_name'];
$room=$_GET['room'];
$room_id=$_GET['room_id'];
$id=$_GET['id'];
$hn=$_GET['hn'];
$fname=$_GET['fname'];
$lname=$_GET['lname'];
$call_server=$_GET['call_server'];
$patient_type=$_GET['patient_type'];


if($_GET['call_server']==""){
	if($_POST['words']!=""){
    $text = substr($_POST['words'], 0, 500);
	}
	if($_GET['words']!=""){
//ค้นหานามสกุล
mysql_select_db($database_hos, $hos);
$query_rs_patient2 = "select * from ".$database_kohrx.".kohrx_queue_patient_name_spell where name='".$words."'";
$rs_patient2 = mysql_query($query_rs_patient2, $hos) or die(mysql_error());
$row_rs_patient2 = mysql_fetch_assoc($rs_patient2);
$totalRows_rs_patient2 = mysql_num_rows($rs_patient2);
//ค้นหาอายุผู้ป่วย
mysql_select_db($database_hos, $hos);
$query_rs_age = "select age_y from vn_stat where hn='".$hn."' order by vstdate DESC LIMIT 1";
$rs_age = mysql_query($query_rs_age, $hos) or die(mysql_error());
$row_rs_age = mysql_fetch_assoc($rs_age);
$totalRows_rs_age = mysql_num_rows($rs_age);
$age=$row_rs_age['age_y'];
mysql_free_result($rs_age);

if($totalRows_rs_patient2<>0){
	$name=$row_rs_patient2['spell'];
	}
else {$name=$words;}

    $text = substr($name, 0, 500);

mysql_free_result($rs_patient2);

	}

	if($_GET['fname']!=""){
	//ค้นหาการเรียกคำนำหน้าชื่อ
	mysql_select_db($database_hos, $hos);
	$query_rs_pname = "select * from ".$database_kohrx.".kohrx_queue_caller_pname l where pname='$prefix'";
	$rs_pname = mysql_query($query_rs_pname, $hos) or die(mysql_error());
	$row_rs_pname = mysql_fetch_assoc($rs_pname);
	$totalRows_rs_pname = mysql_num_rows($rs_pname);
	
	/// ถ้าค้นแล้วมีคิวที่จะต้องเรียก
		if($totalRows_rs_pname<>0){
			/// ถ้าคำนำหน้าชื่อต้องเรียกแบบพิเศษ
			if($row_rs_pname['monk']=="Y"){
				$ppname="นิมนต์";
				}
			else {
				$ppname="ขอเชิญ";
				if($row_rs_pname['parent_call']=="Y"&&$age<12){
					$ppname.="ผู้ปกครอง";
					}
				}
				$prefix=$ppname.$row_rs_pname['pname_call'];
			echo $prefix;
			}
		else {
			$prefix="ขอเชิญคุณ";
			}

//ค้นหาชื่อ
mysql_select_db($database_hos, $hos);
$query_rs_patient = "select * from ".$database_kohrx.".kohrx_queue_patient_name_spell where name='$fname'";
$rs_patient = mysql_query($query_rs_patient, $hos) or die(mysql_error());
$row_rs_patient = mysql_fetch_assoc($rs_patient);
$totalRows_rs_patient = mysql_num_rows($rs_patient);
if($totalRows_rs_patient<>0){
	$firstname=$row_rs_patient['spell'];
	}
else {$firstname=$fname;}

//ค้นหานามสกุล
mysql_select_db($database_hos, $hos);
$query_rs_patient2 = "select * from ".$database_kohrx.".kohrx_queue_patient_name_spell where name='$lname'";
$rs_patient2 = mysql_query($query_rs_patient2, $hos) or die(mysql_error());
$row_rs_patient2 = mysql_fetch_assoc($rs_patient2);
$totalRows_rs_patient2 = mysql_num_rows($rs_patient2);
if($totalRows_rs_patient2<>0){
	$lastname=$row_rs_patient2['spell'];
	}
else {$lastname=$lname;}

	$text = substr($prefix.$firstname." ".$lastname." ".$_GET['second'].$_GET['channel_name'].$_GET['subfix'], 0, 500);

mysql_free_result($rs_patient);
mysql_free_result($rs_patient2);

}
$textlen=strlen(urlencode($text));
echo "<meta http-equiv=\"refresh\" content=\"0;URL=https://translate.google.com.vn/translate_tts?ie=UTF-8&q=".urlencode($text)."&tl=th-TH&client=tw-ob";
}
if($hn!=""){
	//ค้นหา rx queue ของผู้ป่วย hn นี้
	mysql_select_db($database_hos, $hos);
	$query_rs_rx = "select queue from ".$database_kohrx.".kohrx_queued where hn='$hn' and substr(queue_datetime,1,10)=CURDATE() and room_id='".$room_id."' ";
	$rs_rx = mysql_query($query_rs_rx, $hos) or die(mysql_error());
	$row_rs_rx = mysql_fetch_assoc($rs_rx);
	$totalRows_rs_rx = mysql_num_rows($rs_rx);

		$rx_queue=$row_rs_rx['queue'];
		$last_queue=$rx_queue-1;
	
	mysql_free_result($rs_rx);	
	


	//ค้นหา rx queue ของคิวที่ผ่านมาว่ามีการเรียกหรือยัง
	if($rx_queue>1){
	mysql_select_db($database_hos, $hos);
	$query_rs_rx2 = "select queue from ".$database_kohrx.".kohrx_queued q left outer join ".$database_kohrx.".kohrx_queue_caller_list l on l.hn=q.hn and l.room_id=q.room_id and substr(q.queue_datetime,1,10)=substr(l.call_datetime,1,10) where substr(queue_datetime,1,10)=CURDATE() and q.room_id='".$room_id."' and q.queue='".($rx_queue-1)."' and l.called='Y' ";
	$rs_rx2 = mysql_query($query_rs_rx2, $hos) or die(mysql_error());
	$row_rs_rx2 = mysql_fetch_assoc($rs_rx2);
	$totalRows_rs_rx2 = mysql_num_rows($rs_rx2);

		$rx_queue2=$row_rs_rx2['queue'];
	
	mysql_free_result($rs_rx2);	
	}
	
//ค้นหาคนที่เคยเรียกแล้ว
	mysql_select_db($database_hos, $hos);
	$query_rs_call = "select TIME_TO_SEC(TIMEDIFF(NOW(),call_datetime)) as difftime,id from ".$database_kohrx.".kohrx_queue_caller_list where hn='".$hn."' and substr(call_datetime,1,10)=CURDATE() order by id DESC limit 1";
	$rs_call = mysql_query($query_rs_call, $hos) or die(mysql_error());
	$row_rs_call = mysql_fetch_assoc($rs_call);
	$totalRows_rs_call = mysql_num_rows($rs_call);

if($row_rs_call['difftime']>10||$totalRows_rs_call==0){
mysql_select_db($database_hos, $hos);
$insert = "insert into ".$database_kohrx.".kohrx_queue_caller_list (patient_name,channel_id,room_id,call_datetime,hn,call_server,patient_type,doctorcode,rx_queue) value ('".$patient_name."','".$channel."','".$room_id."',NOW(),'".$hn."','".$_GET['call_server']."','".$_GET['patient_type']."','".$_SESSION['doctorcode']."','".$rx_queue."')";
$query_insert = mysql_query($insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_queue_caller_list (patient_name,channel_id,room_id,call_datetime,hn,call_server,patient_type,doctorcode) value (\'".$patient_name."\',\'".$channel."\',\'".$room_id."\',NOW(),\'".$hn."\',\'".$_GET['call_server']."\',\'".$_GET['patient_type']."\',\'".$_SESSION['doctorcode']."\',\'".$rx_queue."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

//ค้นหาคนที่เคยเรียกแล้ว
	mysql_select_db($database_hos, $hos);
	$query_rs_call2 = "select * from ".$database_kohrx.".kohrx_queue_caller_list where hn='$hn' and substr(call_datetime,1,10)=CURDATE() ";
	$rs_call2 = mysql_query($query_rs_call2, $hos) or die(mysql_error());
	$row_rs_call2 = mysql_fetch_assoc($rs_call2);
	$totalRows_rs_call2 = mysql_num_rows($rs_call2);

mysql_select_db($database_hos, $hos);
$insert = "insert into ".$database_kohrx.".kohrx_queue_caller_history (patient_name,channel_id,room_id,call_datetime,hn,patient_type,doctorcode) value ('".$patient_name."','".$channel."','".$room_id."','".$row_rs_call2['call_datetime']."','".$hn."','".$_GET['patient_type']."','".$_SESSION['doctorcode']."')";
$query_insert = mysql_query($insert, $hos) or die(mysql_error());
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_queue_caller_history (patient_name,channel_id,room_id,call_datetime,hn,call_server,patient_type,doctorcode) value (\'".$patient_name."\',\'".$channel."\',\'".$room_id."\',\'".$row_rs_call2['call_datetime']."\',\'".$hn."\',\'".$_GET['patient_type']."\',\'".$_SESSION['doctorcode']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

	mysql_free_result($rs_call2);

mysql_free_result($rs_call);


}
else if($row_rs_call['difftime']<=10)
{
mysql_select_db($database_hos, $hos);
$update = "update ".$database_kohrx.".kohrx_queue_caller_list set channel_id='$channel',room_id='".$room_id."',call_datetime=NOW(),call_server='".$_GET['call_server']."',called =NULL,patient_type='".$_GET['patient_type']."',dispensed=NULL,doctorcode='".$_SESSION['doctorcode']."',rx_queue='".$rx_queue."',main_dep_queue=NULL where hn='".$hn."' and id='".$row_rs_call['id']."' and SUBSTR(call_datetime,1,10)=CURDATE()";
$query_update = mysql_query($update, $hos) or die(mysql_error());	
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_queue_caller_list set channel_id=\'".$channel."\',room_id=\'".$room_id."\',call_datetime=NOW(),call_server=\'".$_GET['call_server']."\',called =NULL,patient_type=\'".$_GET['patient_type']."\',dispensed=NULL,rx_queue=\'".$rx_queue."\',main_dep_queue=NULL  where hn=\'".$hn."\' and SUBSTR(call_datetime,1,10)=CURDATE()')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

	mysql_select_db($database_hos, $hos);
	$query_rs_call = "select * from ".$database_kohrx.".kohrx_queue_caller_list where hn='".$hn."' and substr(call_datetime,1,10)=CURDATE() ";
	$rs_call = mysql_query($query_rs_call, $hos) or die(mysql_error());
	$row_rs_call = mysql_fetch_assoc($rs_call);
	$totalRows_rs_call = mysql_num_rows($rs_call);

mysql_select_db($database_hos, $hos);
$insert = "insert into ".$database_kohrx.".kohrx_queue_caller_history (patient_name,channel_id,room_id,call_datetime,hn,patient_type,doctorcode) value ('".$patient_name."','".$channel."','".$room_id."','".$row_rs_call['call_datetime']."','".$hn."','".$_GET['patient_type']."','".$_SESSION['doctorcode']."')";
$query_insert = mysql_query($insert, $hos) or die(mysql_error());
	//insert replicate_lot
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_queue_caller_history (patient_name,channel_id,room_id,call_datetime,hn,call_server,patient_type,doctorcode) value (\'".$patient_name."\',\'".$channel."\',\'".$room_id."\',\'".$row_rs_call['call_datetime']."\',\'".$hn."\',\'".$_GET['patient_type']."\',\'".$_SESSION['doctorcode']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

mysql_free_result($rs_call);

}

		mysql_select_db($database_hos, $hos);
		$update = "update ".$database_kohrx.".kohrx_queue_caller_list set not_response = NULL where hn='".$hn."' and room_id='".$room_id."'";
		$query_update = mysql_query($update, $hos) or die(mysql_error());

}
?>
<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" lang="en-US">
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" lang="en-US">
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html lang="en-US">
<!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
html, body
{
  height: 95%;
}
</style>
<link href="css/kohrx.css" rel="stylesheet" type="text/css">
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr class="orange">
    <td align="center" class="rounded_top" ><strong>ระบบเรียกชื่อผู้ป่วย</strong></td>
  </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="height:100%">
  <tr>
    <td align="center" class="big_black16 rounded_bottom" style="border-left: 1px #FF6600 solid;border-right: 1px #FF6600 solid; border-bottom: 1px #FF6600 solid;"><?php echo $text; ?><br>
<br>

    <div align="center">
<audio controls="controls" autoplay="autoplay">
  <source src="<?php echo $file; ?>" type="audio/mp3" />
</audio>
</div>
    </td>
  </tr>
</table>

</body>
</html>
<?php
?>