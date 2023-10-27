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

date_default_timezone_set("Asia/Bangkok");
$get_ip=$_SERVER["REMOTE_ADDR"]; 

if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){
	/// ค้นหาข้อมูลเครื่อง //////////
	if(isset($_POST['caller_tv'])&&($_POST['caller_tv']=="Y")){
			$caller_tv="'".$_POST['caller_tv']."'";
			$caller_tv2="\'".$_POST['caller_tv']."\'";
		}
	else {
			$caller_tv="NULL";
			$caller_tv2="NULL";
		}
	if($_POST['queue_method']==2){
		$caller_method=2;
		$queue_display=1;
	}
	else{
		$caller_method=$_POST['caller_method'];	
		$queue_display=$_POST['queue_display'];
	}
	
	mysql_select_db($database_hos, $hos);
$query_rs_search = "select * from ".$database_kohrx.".kohrx_queue_caller_channel where ip='".$get_ip."'";
$rs_search = mysql_query($query_rs_search, $hos) or die(mysql_error());
$row_rs_search = mysql_fetch_assoc($rs_search);
$totalRows_rs_search = mysql_num_rows($rs_search);

	if($totalRows_rs_search==0){
	mysql_select_db($database_hos, $hos);
	$query_rs_insert = "insert into ".$database_kohrx.".kohrx_queue_caller_channel (ip,channel,room_id,call_server,time_per_case,caller_tv,caller_method,queue_method,queue_display) value ('$get_ip','".$_POST['channel']."','".$_POST['room']."','Y','".$_POST['time_per_case']."',".$caller_tv.",".$caller_method.",'".$_POST['queue_method']."',".$queue_display.")";
	$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_queue_caller_channel (ip,channel,room_id,call_server,time_per_case,caller_tv,caller_method,queue_method,queue_display) value (\'".$get_ip."\',\'".$_POST['channel']."\',\'".$_POST['room']."\',\'Y\',\'".$_POST['time_per_case']."\',".$caller_tv2.",".$caller_method.",\'".$_POST['queue_method']."\',".$queue_display.")')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

		}
		else {
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_queue_caller_channel set channel='".$_POST['channel']."',room_id='".$_POST['room']."',call_server='Y',depcode=NULL,q_number=NULL,q_dep_type=NULL,time_per_case='".$_POST['time_per_case']."',caller_method=".$caller_method.",caller_tv=".$caller_tv.",queue_method='".$_POST['queue_method']."',queue_display=".$queue_display." where ip='".$get_ip."'";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_queue_caller_channel set channel=\'".$_POST['channel']."\',room_id=\'".$_POST['room']."\',call_server=\'Y\',depcode=NULL,q_number=NULL,q_dep_type=NULL,time_per_case=\'".$_POST['time_per_case']."\',caller_method=".$caller_method.",caller_tv=".$caller_tv2.",queue_method=\'".$_POST['queue_method']."\',queue_display=".$queue_display." where ip=\'".$get_ip."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
		

		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_kiosk_queue_caller_config set current_q='".$_POST['first_queue']."',current_q_datetime=NOW() where room_id='".$_POST['room']."'";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());


			}

		if($_POST['queue_method']==1){
		mysql_select_db($database_hos, $hos);
		$query_rs_search = "select * from ".$database_kohrx.".kohrx_queued where room_id='".$_POST['room']."' and substr(queue_datetime,1,10)=CURDATE() and queue >'".$_POST['first_queue']."' ";
		$rs_search = mysql_query($query_rs_search, $hos) or die(mysql_error());
		$row_rs_search = mysql_fetch_assoc($rs_search);
		$totalRows_rs_search = mysql_num_rows($rs_search);
		
		do{
		mysql_select_db($database_hos, $hos);
		$query_rs_insert = "delete from ".$database_kohrx.".kohrx_queue_caller_list where room_id='".$_POST['room']."' and hn='".$row_rs_search['hn']."' and substr(call_datetime,1,10)=CURDATE() ";
		$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());
		}while(	$row_rs_search = mysql_fetch_assoc($rs_search));
	
	mysql_free_result($rs_search);
	}
		
		else if($_POST['queue_method']==2){
		
		mysql_select_db($database_hos, $hos);
		$query_rs_insert = "delete from ".$database_kohrx.".kohrx_queue_caller_list where room_id='".$_POST['room']."' and hn='' and substr(call_datetime,1,10)=CURDATE() and main_dep_queue > '".$_POST['first_queue']."'";
		$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());
		
		mysql_select_db($database_hos, $hos);
		$query_rs_insert = "update ".$database_kohrx.".kohrx_kiosk_queue_caller_config set q_date=CURDATE(),current_q='".$_POST['first_queue']."',current_q_datetime=CURDATE() where room_id='".$_POST['room']."' ";
		$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());
		
		}
		
	echo "<script>alert('บันทึกข้อมูลเรียกร้อย');
	window.location='queue_caller_kiosk.php';
	</script>";
	exit();

	}

mysql_select_db($database_hos, $hos);
$query_rs_dep = "select depcode,department from kskdepartment order by depcode";
$rs_dep = mysql_query($query_rs_dep, $hos) or die(mysql_error());
$row_rs_dep = mysql_fetch_assoc($rs_dep);
$totalRows_rs_dep = mysql_num_rows($rs_dep);

mysql_select_db($database_hos, $hos);
$query_rs_room = "select * from ".$database_kohrx.".kohrx_queue_caller_room";
$rs_room = mysql_query($query_rs_room, $hos) or die(mysql_error());
$row_rs_room = mysql_fetch_assoc($rs_room);
$totalRows_rs_room = mysql_num_rows($rs_room);

mysql_select_db($database_hos, $hos);
$query_rs_channel = "select * from ".$database_kohrx.".kohrx_queue_caller_channel_name";
$rs_channel = mysql_query($query_rs_channel, $hos) or die(mysql_error());
$row_rs_channel = mysql_fetch_assoc($rs_channel);
$totalRows_rs_channel = mysql_num_rows($rs_channel);

mysql_select_db($database_hos, $hos);
$query_rs_iam = "select * from ".$database_kohrx.".kohrx_queue_caller_channel where ip='".$get_ip."'";
$rs_iam = mysql_query($query_rs_iam, $hos) or die(mysql_error());
$row_rs_iam = mysql_fetch_assoc($rs_iam);
$totalRows_rs_iam = mysql_num_rows($rs_iam);

mysql_select_db($database_hos, $hos);
$query_rs_q_config = "select * from ".$database_kohrx.".kohrx_kiosk_queue_caller_config where room_id='".$row_rs_iam['room_id']."' ";
$rs_q_config = mysql_query($query_rs_q_config, $hos) or die(mysql_error());
$row_rs_q_config = mysql_fetch_assoc($rs_q_config);
$totalRows_rs_q_config = mysql_num_rows($rs_q_config);
	
	if($totalRows_rs_q_config==0){

		mysql_select_db($database_hos, $hos);
		$query_rs_insert = "insert into ".$database_kohrx.".kohrx_kiosk_queue_caller_config (room_id,current_q,q_date,current_q_datetime) value ('".$row_rs_iam['room_id']."','0',NOW(),NOW()) ";
		$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());
		}
	else{
			if($row_rs_q_config['q_date']!=date('Y-m-d')){
			mysql_select_db($database_hos, $hos);
			$query_rs_insert = "update ".$database_kohrx.".kohrx_kiosk_queue_caller_config set current_q='0',q_date=NOW(),current_q_datetime=NOW() where room_id='".$row_rs_iam['room_id']."' ";
			$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());		
			}
		}
    if(isset($_POST['delete'])&&($_POST['delete']=="ลบคิว")){
		
		if($row_rs_iam['queue_method']==1){
		mysql_select_db($database_hos, $hos);
		$query_rs_search = "select * from ".$database_kohrx.".kohrx_queued where room_id='".$row_rs_iam['room_id']."' and substr(queue_datetime,1,10)=CURDATE() and queue >='".$_POST['q_delete']."' ";
		$rs_search = mysql_query($query_rs_search, $hos) or die(mysql_error());
		$row_rs_search = mysql_fetch_assoc($rs_search);
		$totalRows_rs_search = mysql_num_rows($rs_search);
		
		do{
		mysql_select_db($database_hos, $hos);
		$query_rs_insert = "delete from ".$database_kohrx.".kohrx_queue_caller_list where room_id='".$row_rs_iam['room_id']."' and hn='".$row_rs_search['hn']."' and substr(call_datetime,1,10)=CURDATE() ";
		$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());
		}while(	$row_rs_search = mysql_fetch_assoc($rs_search));
		
		mysql_free_result($rs_search);
		
		mysql_select_db($database_hos, $hos);
		$query_rs_insert = "update ".$database_kohrx.".kohrx_queued set called_datetime=NULL where room_id='".$row_rs_iam['room_id']."' and substr(called_datetime,1,10)=CURDATE() and queue >='".$_POST['q_delete']."' ";
		$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());
		
		mysql_select_db($database_hos, $hos);
		$query_rs_insert = "update ".$database_kohrx.".kohrx_kiosk_queue_caller_config set q_date=CURDATE(),current_q='".$_POST['q_delete']."',current_q_datetime=CURDATE() where room_id='".$row_rs_iam['room_id']."' ";
		$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());

		}
		else if($row_rs_iam['queue_method']==2){
		
		mysql_select_db($database_hos, $hos);
		$query_rs_insert = "delete from ".$database_kohrx.".kohrx_queue_caller_list where room_id='".$row_rs_iam['room_id']."' and hn='' and substr(call_datetime,1,10)=CURDATE() and main_dep_queue >= '".$_POST['q_delete']."'";
		$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());
		
		mysql_select_db($database_hos, $hos);
		$query_rs_insert = "update ".$database_kohrx.".kohrx_kiosk_queue_caller_config set q_date=CURDATE(),current_q='".$_POST['q_delete']."',current_q_datetime=CURDATE() where room_id='".$row_rs_iam['room_id']."' ";
		$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());
		
		}
		
		
	
	echo "<script>alert('ลบข้อมูลแล้วเรียกร้อย');
	window.location='queue_caller_kiosk.php';
	</script>";
	exit();


}

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ระบบเรียกคิว</title>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" href="include/bootstrap3/css/bootstrap.min.css">
  <script src="include/bootstrap3/js/jquery.min.js"></script>
  <script src="include/bootstrap3/js/bootstrap.min.js"></script>
  <script>
  	$(document).ready(function() {
		setInterval(reloadQueue, 1000);  
		if(<?php echo $row_rs_iam['queue_method']; ?>==2){
				$(".caller_div").hide();  				
		}
		$('#queue_method').change(function(){
			if($('#queue_method').val()==2){
				$(".caller_div").hide();  
			}
			if($('#queue_method').val()==1){
				$(".caller_div").show();  
			}
			});
	});

  	function reloadQueue () {

     $('#recent_queue').load('queue_caller_kiosk_recent.php');
	}
	function queue_call(e){
     $('#next_queue').load('queue_caller_kiosk_next.php?action='+e+'&input_q='+$('#input_q').val());		
	}
	
	function config_q(q){
	$('#first_queue').val(q);	
	}
	function config_q2(){
		if($('#first_queue').val()<$('#input_q').val()){
			$('#first_queue').val($('#input_q').val());	
		}
	}
		
	$(document).on({
    "contextmenu": function(e) {
        console.log("ctx menu button:", e.which); 

        // Stop the context menu
        e.preventDefault();
    },
    "mousedown": function(e) { 
        console.log("normal mouse down:", e.which); 
    },
    "mouseup": function(e) { 
        console.log("normal mouse up:", e.which); 
    }
});
  </script>
<style>
html, body {
    max-width: 100%;
    overflow-x: hidden;
}
</style>
</head>

<body onLoad="reloadQueue();">
<form method="post" action="queue_caller_kiosk.php">
<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home"><i class="fas fa-home">&nbsp;คิวอัตโนมัติ</i></a></li>
    <li ><a data-toggle="tab" href="#number"><i class="fas fa-keyboard">&nbsp;กำหนดคิว</i></a></li>
    <li><a data-toggle="tab" href="#config"><i class="fas fa-cog">&nbsp;ตั้งค่า</i></a></li>
  </ul>

  <div class="tab-content" style="padding-left:10px;">
    <div id="home" class="tab-pane fade in active">
      <div class="container" align="center">
			<div id="recent_queue" align="center" style="padding-top:10px;" ></div>
			<div id="next_queue" align="center" style="font-size:20px;"></div>
    	</div>
    </div>
    <div id="number" class="tab-pane fade" align="center">
	<div align="center" style="font-size:20px;">คิว</div>
    <div align="center">
    <input type="number" class="form-control" id="input_q" name="input_q" style="width:200px; font-size:80px; height:100px; text-align:center"/>
               <a href="javascript:valid()" id="next" onClick="queue_call('manual');config_q2();" name="next" class=" btn btn-primary btn-lg" style="margin-top:10px;"><i class="fas fa-microphone-alt" style="font-size:30px;"></i>&nbsp;เรียกคิว</a>

    </div>
    </div>
    <div id="config" class="tab-pane fade" style="padding:10px;">
		<div class="form-group row">

    <label class="col-sm-2" for="first_queue">คิวเริ่มต้น</label>
        	<div class="col-sm-2">
        <input type="text" id="first_queue" name="first_queue" class="form-control" value="<?php if($totalRows_rs_q_config<>0){ echo $row_rs_q_config['current_q']; } else { echo "1"; } ?>"/>
    		</div>
    	</div>

 	<div class="form-group row">
    <label class="col-sm-2" for="room">ห้องของระบบเรียกคิว</label>
        <div class="col-sm-2">
		<select name="room" id="room" class="form-control">
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
 	<div class="form-group row">
    <label class="col-sm-2" for="channel">ช่องที่เรียกคิว</label>
        <div class="col-sm-2">
		<select name="channel" id="channel" class="form-control">
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
   <div class="form-group row">  
    <label class=" col-sm-2 form-check-label" for="queue_method">วิธีออกบัตรคิว</label>
    <div class=" col-sm-2">
    <select class="form-control" id="queue_method" name="queue_method">
      <option value="1" <?php if (!(strcmp("1", $row_rs_iam['queue_method']))) {echo "selected=\"selected\"";} ?>>ออกโดยตู้ kiosk</option>
      <option value="2" <?php if (!(strcmp("2", $row_rs_iam['queue_method']))) {echo "selected=\"selected\"";} ?>>ออกโดยบัตรคิวทำเอง</option>    
    </select>
    
  </div>
  </div>
   <div class="form-group row caller_div">  
    <label class=" col-sm-2 form-check-label" for="caller_method">วิธีการเรียก</label>
    <div class=" col-sm-2">
    <select class="form-control" id="caller_method" name="caller_method" >
      <option value="1" <?php if (!(strcmp("1", $row_rs_iam['caller_method']))) {echo "selected=\"selected\"";} ?>>เรียกชื่อผู้ป่วย</option>
      <option value="2" <?php if (!(strcmp("2", $row_rs_iam['caller_method']))) {echo "selected=\"selected\"";} ?>>เรียกคิว</option>    
      <option value="3" <?php if (!(strcmp("3", $row_rs_iam['caller_method']))) {echo "selected=\"selected\"";} ?>>เรียกคิว+ชื่อ</option>    
    </select>
    
  </div>
  </div>
   <div class="form-group row caller_div" >  
    <label class=" col-sm-2 form-check-label" for="queue_display">การแสดงบนหน้าจอทีวี</label>
    <div class=" col-sm-2">
    <select class="form-control" id="queue_display" name="queue_display" >
      <option value="1" <?php if (!(strcmp("1", $row_rs_iam['queue_display']))) {echo "selected=\"selected\"";} ?>>แสดงแบบที่ 1</option>
      <option value="2" <?php if (!(strcmp("2", $row_rs_iam['queue_display']))) {echo "selected=\"selected\"";} ?>>แสดงแบบที่ 2</option>    
    </select>
    
  </div>
  </div>
 
   <div class="form-group row">  
    <label class=" col-sm-2 form-check-label" for="caller_tv">เรียกคิวผ่านหน้าจอโทรทัศน์</label>
    <div class=" col-sm-1">
<input <?php if (!(strcmp($row_rs_iam['caller_tv'],"Y"))) {echo "checked=\"checked\"";} ?> name="caller_tv" type="checkbox" id="caller_tv" value="Y" />    
  </div>
  </div>
    <div class="form-group row">  
    <label class=" col-sm-2 form-check-label" for="time_per_case">เวลาที่ใช้ต่อรายผู้ป่วย(นาที)</label>
    <div class=" col-sm-1">
    <select class="form-control" id="time_per_case" name="time_per_case">
        <?php for($i=1;$i<=30;$i++){ ?>
          <option value="<?php echo $i; ?>" <?php if (!(strcmp($i, $row_rs_iam['time_per_case']))) {echo "selected=\"selected\"";} ?>><?php echo $i; ?></option>
         <?php } ?>
    </select>
    
  </div>
  </div>
 	<div class="form-group row">
    <label class="col-sm-2" >IP ประจำเครื่อง</label>
        <div class="col-sm-2">
        <span style="color:#FF0000; font-size:18px;"><?php echo $get_ip; ?></span>
		</div>
     </div>
 	<div class="form-group row">
    <label class="col-sm-2" ></label>    
        <div class="col-sm-12">
		<input type="submit" id="save" name="save" class="btn btn-success" value="บันทึก"/>        
        </div>
    </div>
    <hr> 
 	<div class="form-group row">
    <label class="col-sm-2" for="q_delete" >ลบคิวที่ถูกเรียกแล้วตั้งแต่คิว</label>    
        <div class="col-sm-1">
		<input type="number" id="q_delete" name="q_delete" class=" form-control" value="0" />  
        </div>
     <div class="col-sm-1">เป็นต้นไป</div>     
     <div class="col-sm-1">
		<input type="submit" id="delete" name="delete" class="btn btn-danger" value="ลบคิว"/>        
        </div>
* เป็นการลบคิวในระบบเรียกคิวในห้องกำหนด  ไม่เกี่ยวข้องกับระบบคิวใน hosxp
    </div>
                         
    </div>
  </div>
</form>
</body>
</html>
<?php
mysql_free_result($rs_dep);

mysql_free_result($rs_room);

mysql_free_result($rs_channel);


mysql_free_result($rs_iam);

mysql_free_result($rs_q_config);
?>
