<?php require_once('Connections/hos.php'); ?>
<?php 
ob_start();
session_start();
?>
<?php require('include/get_channel.php'); ?>
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

function doctorname($doctor){
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs_doctor = "SELECT name from doctor where code='".$doctor."'";
    $rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
    $row_rs_doctor = mysql_fetch_assoc($rs_doctor);    
    
    $doctorname= $row_rs_doctor['name'];
    
    mysql_free_result($rs_doctor);
    return $doctorname;
}
function doctorcode2username($doctor){
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs_doctor = "SELECT loginname from opduser where doctorcode='".$doctor."'";
    $rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
    $row_rs_doctor = mysql_fetch_assoc($rs_doctor);    
    
    $doctorname= $row_rs_doctor['loginname'];
    
    mysql_free_result($rs_doctor);
    return $doctorname;
}
function username2doctorcode($doctor){
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs_doctor = "SELECT doctorcode from opduser where loginname='".$doctor."'";
    $rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
    $row_rs_doctor = mysql_fetch_assoc($rs_doctor);    
    
    $doctorname= $row_rs_doctor['doctorcode'];
    
    mysql_free_result($rs_doctor);
    return $doctorname;
}

if(isset($_GET['action'])&&($_GET['action']=="delete")){
    $table=$_GET['doctor_type'];
    mysql_select_db($database_hos, $hos);
    $insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'PHARM','delete from ipt_dispense_".$table." where order_no=\'".$_GET['order_no']."\'')";
    $rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

    //update serial ใน replicate_log dispens_prepare
    mysql_select_db($database_hos, $hos);
    $delete = "delete from ipt_dispense_".$table." where order_no='".$_GET['order_no']."'";
    $rs_delete = mysql_query($delete, $hos) or die(mysql_error());

    if($table=="prepare"){
    mysql_select_db($database_hos, $hos);
    $insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'PHARM','update ipt_order_no set prepare_staff=NULL,prepare_doctor_code=NULL,depcode=NULL where order_no=\'".$_GET['order_no']."\'')";
    $rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

    //update serial ใน replicate_log dispens_prepare
    mysql_select_db($database_hos, $hos);
    $update = "update ipt_order_no set prepare_staff=NULL,prepare_doctor_code=NULL,depcode=NULL where order_no='".$_GET['order_no']."'";
    $rs_udpate = mysql_query($update, $hos) or die(mysql_error());
    }
    if($table=="pay"){
    mysql_select_db($database_hos, $hos);
    $insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'PHARM','update ipt_order_no set pay_staff=NULL,pay_doctor_code=NULL,pay_depcode=NULL,pay_datetime=NULL where order_no=\'".$_GET['order_no']."\'')";
    $rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

    //update serial ใน replicate_log dispens_prepare
    mysql_select_db($database_hos, $hos);
    $update = "update ipt_order_no set pay_staff=NULL,pay_doctor_code=NULL,pay_depcode=NULL,pay_datetime=NULL where order_no='".$_GET['order_no']."'";
    $rs_udpate = mysql_query($update, $hos) or die(mysql_error());
    }
    
}

if(isset($_GET['action'])&&($_GET['action']=="save")){
//ค้นหา id ของ ipt_dispense_pay_id ของ serial
mysql_select_db($database_hos, $hos);
$query_rs_serial = "select count(*) as cc from serial where name='ipt_dispense_pay_id' ";
$rs_serial = mysql_query($query_rs_serial, $hos) or die(mysql_error());
$row_rs_serial = mysql_fetch_assoc($rs_serial);
$totalRows_rs_serial = mysql_num_rows($rs_serial);

//ค้นหา id ของ ipt_dispense_prepare_id ของ serial
mysql_select_db($database_hos, $hos);
$query_rs_serial1 = "select count(*) as cc from serial where name='ipt_dispense_prepare_id' ";
$rs_serial1 = mysql_query($query_rs_serial1, $hos) or die(mysql_error());
$row_rs_serial1 = mysql_fetch_assoc($rs_serial1);
$totalRows_rs_serial1 = mysql_num_rows($rs_serial1);

//get_serial
mysql_select_db($database_hos, $hos);
$query_rs_get_serial = "select get_serialnumber('ipt_dispense_pay_id') as cc ";
$rs_get_serial = mysql_query($query_rs_get_serial, $hos) or die(mysql_error());
$row_rs_get_serial = mysql_fetch_assoc($rs_get_serial);
$totalRows_rs_get_serial = mysql_num_rows($rs_get_serial);
//$rx_operator_log_id=$row_rs_get_serial['cc'];

//get_serial 
mysql_select_db($database_hos, $hos);
$query_rs_get_serial1 = "select get_serialnumber('ipt_dispense_prepare_id') as cc ";
$rs_get_serial1 = mysql_query($query_rs_get_serial1, $hos) or die(mysql_error());
$row_rs_get_serial1 = mysql_fetch_assoc($rs_get_serial1);
$totalRows_rs_get_serial1 = mysql_num_rows($rs_get_serial1);


//update serial ใน replicate_log dispens_pay
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'PHARM','update serial set serial_no = \'".$row_rs_get_serial['cc']."\' where name =\'ipt_dispense_pay_id\'')";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

//update serial ใน replicate_log dispens_prepare
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'PHARM','update serial set serial_no = \'".$row_rs_get_serial1['cc']."\' where name =\'ipt_dispense_prepare_id\'')
";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());


//เก็บค่าเวลาปัจจบัน
mysql_select_db($database_hos, $hos);
$query_rx_timenow = "select now()as DateTimenow";
$rx_timenow = mysql_query($query_rx_timenow, $hos) or die(mysql_error());
$row_rx_timenow = mysql_fetch_assoc($rx_timenow);
$totalRows_rx_timenow = mysql_num_rows($rx_timenow);

//if($totalRows_rs_search1==0){
//ถ้ายังไม่มีการบันทึก กรณีรายใหม่
if($_GET['dispen']!=""){	
mysql_select_db($database_hos, $hos);
$query_rs_doctor = "SELECT pay_staff FROM ipt_dispense_pay where order_no='".$_GET['order_no']."' ";
$rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
$row_rs_doctor = mysql_fetch_assoc($rs_doctor);
$totalRows_rs_doctor = mysql_num_rows($rs_doctor);
if($totalRows_rs_doctor==0){
//insert ipt_dispen_pay ใน replicate_log
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'PHARM-DISPEN1','INSERT INTO ipt_dispense_pay (ipt_dispense_pay_id,an,order_no,pay_datetime,pay_staff,depcode) VALUES (\'".$row_rs_get_serial['cc']."\',\'".$_GET['an']."\',".$_GET['order_no'].",\'".$row_rx_timenow['DateTimenow']."\',\'".doctorcode2username($_GET['dispen'])."\',\'".$row_channel['kskdepart']."\')')";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

//บันทึกข้อมูลใน rx_operator
mysql_select_db($database_hos, $hos);
$insert_rx = "INSERT INTO ipt_dispense_pay (ipt_dispense_pay_id,an,order_no,pay_datetime,pay_staff,depcode) VALUES ('$row_rs_get_serial[cc]','".$_GET['an']."','".$_GET['order_no']."','".$row_rx_timenow['DateTimenow']."','".doctorcode2username($_GET['dispen'])."','".$row_channel['kskdepart']."')";
    //echo $insert_rx;
$rs_insert_rx = mysql_query($insert_rx, $hos) or die(mysql_error());

mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'PHARM-DISPEN1','update ipt_order_no set pay_staff=\'".doctorcode2username($_GET['dispen'])."\',pay_doctor_code=\'".$_GET['dispen']."\',pay_depcode=\'".$row_channel['kskdepart']."\',pay_datetime=NOW() where order_no=\'".$_GET['order_no']."\'')";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

//บันทึกข้อมูลใน rx_operator
mysql_select_db($database_hos, $hos);
$insert_rx = "update ipt_order_no set pay_staff='".doctorcode2username($_GET['dispen'])."',pay_doctor_code='".$_GET['dispen']."',pay_depcode='".$row_channel['kskdepart']."',pay_datetime=NOW() where order_no='".$_GET['order_no']."'";
$rs_insert_rx = mysql_query($insert_rx, $hos) or die(mysql_error());
    
	/// ค้นหาข้อมูลผู้ป่วยว่ามีการถูกเรียกชื่อหรือไม่
	//ค้นหาชื่อผู้ป่วยว่ามีบันทึกในระบบเรียกชื่อหรือไม่
	mysql_select_db($database_hos, $hos);
	$query_rs_patient = "select l.hn,SUBSTR(call_datetime,1,10) as call_date from ".$database_kohrx.".kohrx_queue_caller_list l left outer join an_stat v on v.hn=l.hn where an='".$_GET['an']."' and SUBSTR(call_datetime,1,10)=CURDATE()";
	$rs_patient = mysql_query($query_rs_patient, $hos) or die(mysql_error());
	$row_rs_patient = mysql_fetch_assoc($rs_patient);
	$totalRows_rs_patient = mysql_num_rows($rs_patient);
if($totalRows_rs_patient<>0){
	mysql_select_db($database_hos, $hos);
	$query_update = "UPDATE ".$database_kohrx.".kohrx_queue_caller_list SET dispensed='Y' WHERE hn='".$row_rs_patient['hn']."' and SUBSTR(call_datetime,1,10)='".$row_rs_patient['call_date']."'";
	$update = mysql_query($query_update, $hos) or die(mysql_error());
	//บันทึกข้อมูการบันทึก  ใน replicate_log
	mysql_select_db($database_hos, $hos);
	$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','UPDATE ".$database_kohrx.".kohrx_queue_caller_list SET dispensed=\'Y\' WHERE hn=\'".$row_rs_patient['hn']."\' and SUBSTR(call_datetime,1,9)=\'".$row_rs_patient['call_date']."\'')";
	$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

	}	

}
mysql_free_result($rs_doctor);
}
//เสร็จสิ้นกรณีบันทึกข้อมูลใหม่
//}

//if($totalRows_rs_search2==0){
//ถ้ายังไม่มีการบันทึก กรณีรายใหม่
if($_GET['prepare']!=""){	
mysql_select_db($database_hos, $hos);
$query_rs_doctor = "SELECT prepare_staff FROM ipt_dispense_prepare where order_no='".$_GET['order_no']."' ";
$rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
$row_rs_doctor = mysql_fetch_assoc($rs_doctor);
$totalRows_rs_doctor = mysql_num_rows($rs_doctor);

if($totalRows_rs_doctor==0){
    
//insert ipt_dispen_prepare ใน replicate_log
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'PHARM','INSERT INTO ipt_dispense_prepare (ipt_dispense_prepare_id,an,order_no,prepare_datetime,prepare_staff,depcode) VALUES (\'".$row_rs_get_serial1['cc']."\',\'".$_GET['an']."\',\'".$_GET['order_no']."\',\'".$row_rx_timenow['DateTimenow']."\',\'".doctorcode2username($_GET['prepare'])."\',\'".$row_channel['kskdepart']."\')')

";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

//บันทึกข้อมูลใน rx_operator
mysql_select_db($database_hos, $hos);
$insert_rx = "INSERT INTO ipt_dispense_prepare (ipt_dispense_prepare_id,an,order_no,prepare_datetime,prepare_staff,depcode) VALUES ('$row_rs_get_serial1[cc]','".$_GET['an']."','".$_GET['order_no']."','".$row_rx_timenow['DateTimenow']."','".doctorcode2username($_GET['prepare'])."','".$row_channel['kskdepart']."')";
$rs_insert_rx = mysql_query($insert_rx, $hos) or die(mysql_error());
//เสร็จสิ้นกรณีบันทึกข้อมูลใหม่

mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'PHARM-DISPEN1','update ipt_order_no set prepare_staff=\'".doctorcode2username($_GET['prepare'])."\',prepare_doctor_code=\'".$_GET['prepare']."\',depcode=\'".$row_channel['kskdepart']."\' where order_no=\'".$_GET['order_no']."\'')";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

//บันทึกข้อมูลใน rx_operator
mysql_select_db($database_hos, $hos);
$insert_rx = "update ipt_order_no set prepare_staff='".doctorcode2username($_GET['prepare'])."',prepare_doctor_code='".$_GET['prepare']."',depcode='".$row_channel['kskdepart']."' where order_no='".$_GET['order_no']."'";
//echo $insert_rx;
$rs_insert_rx = mysql_query($insert_rx, $hos) or die(mysql_error());

}
//}
mysql_free_result($rs_doctor);
}
}

mysql_select_db($database_hos, $hos);
$query_rs_doctor = "SELECT d.name,o.doctorcode FROM ".$database_kohrx.".kohrx_rx_person o left outer join doctor d on d.code=o.doctorcode ";
$rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
$row_rs_doctor = mysql_fetch_assoc($rs_doctor);
$totalRows_rs_doctor = mysql_num_rows($rs_doctor);

//ผู้บันทึก
mysql_select_db($database_hos, $hos);
$query_rs_entry = "select i.* from ipt_order_no i where i.order_no='".$_GET['order_no']."'";
$rs_entry = mysql_query($query_rs_entry, $hos) or die(mysql_error());
$row_rs_entry = mysql_fetch_assoc($rs_entry);
$totalRows_rs_entry = mysql_num_rows($rs_entry);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script>
    $(function(){

        $('#save').click(function(){
                    $("#ipd_save").load('detail_ipd_save.php?action=save&order_no=<?php echo $_GET['order_no']; ?>&an=<?php echo $_GET['an']; ?>&prepare='+$('#prepare').val()+'&dispen='+$('#dispen').val(), function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    if(statusTxt == "error")
                       alert("Error: " + xhr.status + ": " + xhr.statusText);    
               });

			
        });

    });
    
    function deletestaff(doctorcode,order_no,doctor_type){
                    $("#ipd_save").load('detail_ipd_save.php?action=delete&order_no='+order_no+'&doctorcode='+doctorcode+'&doctor_type='+doctor_type, function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    if(statusTxt == "error")
                       alert("Error: " + xhr.status + ": " + xhr.statusText);    
               });
        
    }
</script>

</head>

<body>
	<div class="rounded <?php if($row_rs_entry['prepare_staff']!=""&&$row_rs_entry['pay_staff']!=""){ echo "bg-success";} else {echo "bg-info";} ?> p-2 mt-2 text-white" >
              <div class="form-row ">
                <label for="prepare" class="col-sm-auto col-form-label col-form-label-sm font12"><strong style="color:#000000">ผู้บันทึก :</strong> <?=doctorname(username2doctorcode($row_rs_entry['entry_staff'])); ?>&nbsp;(<?=substr($row_rs_entry['rxtime'],0,5); ?>)</label>                
                <?php if($row_rs_entry['prepare_staff']==""){ ?>
				  <label for="prepare" class="col-sm-auto col-form-label col-form-label-sm font12">ผู้จัด</label>
				<div class="col-sm-auto">
                    <input name="prepare" type="text" class="form-control form-control-sm font12"  id="prepare" style="width: 40px; padding: 5px 2px 5px 2px"  onkeydown="setNextFocus('dispen');" onkeyup="resutName(this.value,'preparedoctor');" value="<?php if(isset($check_staff2)){ echo $row_rs_rx_operator['check_staff']; } else { if($_SESSION['doctor_type']==2){ echo $_SESSION['doctorcode'] ;} } ?>" <?php if($row_setting['35']=="Y"){ ?>onkeypress="return isNumberKey(event);"<?php } ?> <?php if($_SESSION['doctor_type']==2){if(isset($check_staff2)){ echo "style=\"background-color:#FC0\""; }} ?> />   
                </div>
                  <div class="col-sm-auto">
                    <select name="preparedoctor" class="form-control form-control-sm font12" id="preparedoctor" onchange="doctorcode(this.value,'prepare')" style="padding-left:2px; padding-right:2px; width: 120px;" onkeydown="setNextFocus('check');" >
                    <?php
do {  
?>
                    <option value="<?php echo $row_rs_doctor['doctorcode']?>"<?php if(isset($check_staff2)){ if (!(strcmp($row_rs_doctor['doctorcode'],$row_rs_rx_operator['check_staff']))) {echo "selected=\"selected\"";}} else {if($_SESSION['doctor_type']==2){if (!(strcmp($row_rs_doctor['doctorcode'], $_SESSION['doctorcode']))) {echo "selected=\"selected\"";}}  } ?>><?php echo $row_rs_doctor['name']?></option>
                    <?php
} while ($row_rs_doctor = mysql_fetch_assoc($rs_doctor));
  $rows = mysql_num_rows($rs_doctor);
  if($rows > 0) {
      mysql_data_seek($rs_doctor, 0);
	  $row_rs_doctor = mysql_fetch_assoc($rs_doctor);
  }
?>
                  </select>
                      
                  </div>
				 <?php } ?>
				  <?php if($row_rs_entry['prepare_staff']!=""){ ?>
<label class="col-sm-auto col-form-label col-form-label-sm font12"><strong style="color:#000000">ผู้จัด :</strong> <?=doctorname($row_rs_entry['prepare_doctor_code']); ?>&ensp;<i class="fas fa-trash-alt font18" style="cursor: pointer" onClick="if(confirm('ต้องการลบข้อมูลผู้จัดจริงหรือไม่')==true){ deletestaff('<?php echo $row_rs_entry['prepare_staff']; ?>','<?php echo $_GET['order_no']; ?>','prepare'); }"></i></label>   	
				<?php	} ?>
				<?php if($row_rs_entry['pay_staff']==""){ ?>
                <label for="dispen" class="col-sm-auto col-form-label col-form-label-sm font12">ผู้จ่าย</label>
                <div class="col-sm-auto">
                  <input name="dispen" type="text" class="form-control form-control-sm font12" id="dispen" style="width: 40px; padding: 5px 2px 5px 2px" onkeydown="setNextFocus('save');" onkeyup="resutName(this.value,'dispendoctor')" value="<?php echo $_SESSION['doctorcode'] ; ?>" <?php if($row_setting['35']=="Y"){ ?>onkeypress="return isNumberKey(event);"<?php } ?> <?php if($_SESSION['doctor_type']==4){if(isset($pay_staff2)){ echo "style=\"background-color:#FC0\""; }} ?>  />
                    
                </div>  
                  <div class="col-sm-auto">
                    <select name="dispendoctor" class="form-control form-control-sm font12" id="dispendoctor" onchange="doctorcode(this.value,'dispen')" style="padding-left:2px; padding-right:2px; width:120px;" onkeydown="setNextFocus('detail_save');" >
                    <?php
do {  
?>
                    <option value="<?php echo $row_rs_doctor['doctorcode']?>"<?php if(isset($pay_staff2)){ if (!(strcmp($row_rs_doctor['doctorcode'],$row_rs_rx_operator['pay_staff']))) {echo "selected=\"selected\"";}} else {if($_SESSION['doctor_type']==4){if (!(strcmp($row_rs_doctor['doctorcode'], $_SESSION['doctorcode']))) {echo "selected=\"selected\"";}}  } ?>><?php echo $row_rs_doctor['name']?></option>
                    <?php
} while ($row_rs_doctor = mysql_fetch_assoc($rs_doctor));
  $rows = mysql_num_rows($rs_doctor);
  if($rows > 0) {
      mysql_data_seek($rs_doctor, 0);
	  $row_rs_doctor = mysql_fetch_assoc($rs_doctor);
  }
?>
                  </select>
                      
                  </div>
				  <?php } ?>
				  <?php if($row_rs_entry['pay_staff']!=""){ ?>
<label class="col-sm-auto col-form-label col-form-label-sm font12"><strong style="color:#000000">ผู้จ่าย :</strong> <?=doctorname($row_rs_entry['pay_doctor_code']); ?>&nbsp;(<?=substr($row_rs_entry['pay_datetime'],11,5); ?>)&ensp;<i class="fas fa-trash-alt font18" style="cursor: pointer" onClick="if(confirm('ต้องการลบข้อมูลผู้จ่ายจริงหรือไม่')==true){ deletestaff('<?php echo $row_rs_entry['pay_staff']; ?>','<?php echo $_GET['order_no']; ?>','pay'); }"></i></label>   	
				<?php	} ?>
				  
				  <?php if($row_rs_entry['pay_staff']==""||$row_rs_entry['prepare_staff']==""){ ?>
                  <div class="col-sm-auto"><input type="button" id="save" value="บันทึก" class="btn btn-primary btn-sm"  /></div>
				  <?php } ?>
				  <?php if($row_rs_entry['order_type']=="Hme"){ ?>
                  <div class="col-sm-2"><button id="homemed" class="btn btn-dark btn-sm" onclick="alertload('home_med.php?an=<?php echo $an; ?>&order_no=<?php echo $order_no; ?>','800','90%');"><i class="fas fa-print font20"></i>&nbsp;HM</button></div>
                <?php } ?>

              </div>

	</div>
</body>
</html>
<?php
if(isset($_GET['action'])&&($_GET['action']=="save")){

mysql_free_result($rs_serial);

mysql_free_result($rs_serial1);
//ปิด sql
mysql_free_result($rx_timenow);

mysql_free_result($channel);

}
mysql_free_result($rs_doctor);

mysql_free_result($rs_entry);

?>
