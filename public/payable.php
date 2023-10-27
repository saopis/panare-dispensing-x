<?
ob_start();
session_start();
?>
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

include('include/function.php');

if(isset($_POST['button'])&&($_POST['button']=="บันทึก")){

$date11=explode("/",$_POST['date1']);
$edate1=(($date11[2]-543)."-".$date11[1]."-".$date11[0]);

$date11=explode("/",$_POST['date2']);
$edate2=(($date11[2]-543)."-".$date11[1]."-".$date11[0]);

mysql_select_db($database_hos, $hos);
$insert = "insert into ".$database_kohrx.".kohrx_payable (hn,date_service,drug,date_payable,among,remark,doctor) value ('".$_POST['hn']."','".$edate1."','".$_POST['drugname']."','".$edate2."','".$_POST['qty']."','".$_POST['remark']."','".$_POST['doctor']."')";
$rs_insert = mysql_query($insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_payable (hn,date_service,drug,date_payable,among,remark,doctor) value (\'".$_POST['hn']."\',\'".$edate1."\',\'".$_POST['drugname']."\',\'".$edate2."\',\'".$_POST['qty']."\',\'".$_POST['remark']."\',\'".$_POST['doctor']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

if($rs_insert){
    echo "<script>parent.$.fn.colorbox.close();parent.accrued_load('".$_POST['hn']."');</script>";
    exit();
    
	}
}
if($_GET['id']!=""){
mysql_select_db($database_hos, $hos);
$query_edit = "SELECT * from ".$database_kohrx.".kohrx_payable where id ='".$_GET['id']."'";
$rs_edit = mysql_query($query_edit, $hos) or die(mysql_error());
$row_rs_edit = mysql_fetch_assoc($rs_edit);
$totalRows_rs_edit = mysql_num_rows($rs_edit);

$esdate1=date_db2th($row_rs_edit['date_service']);

$esdate2=date_db2th($row_rs_edit['date_payable']);
	
$icode=$row_rs_edit['drug'];

	}


if(isset($_POST['button'])&&$_POST['button']=="แก้ไข"){
	$edate1=date_th2db($_POST['date1']);

	$edate2=date_th2db($_POST['date2']);

	if(isset($confirm)&&$confirm=="Y"){
	$condition=",date_pay=NOW()";
	}
	if(!isset($confirm)){
	$condition=",date_pay= null";
	}

	mysql_select_db($database_hos, $hos);
	$query_update = "update ".$database_kohrx.".kohrx_payable set date_service='".$edate1."',drug='".$_POST['drugname']."',date_payable='".$edate2."',among='".$_POST['qty']."',remark='".$_POST['remark']."',doctor='".$_POST['doctor']."' ".$condition." where id ='".$_POST['id']."'";
	$rs_update = mysql_query($query_update, $hos) or die(mysql_error());
	
		//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_payable set date_service=\'".$edate1."\',drug=\'".$_POST['drugname']."\',date_payable=\'".$edate2."\',among=\'".$_POST['qty']."\',remark=\'".$_POST['remark']."\',doctor=\'".$_POST['doctor']."\'".$condition." where id =\'".$_POST['id']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

	if($rs_update){
    echo "<script>parent.$.fn.colorbox.close();parent.accrued_load('".$_POST['hn']."');</script>";
    exit();
    }

	}

if(isset($_POST['button2'])&&$_POST['button2']=="ลบข้อมูล"){
	mysql_select_db($database_hos, $hos);
	$query_delete = "delete from ".$database_kohrx.".kohrx_payable where id ='$id'";
	$rs_delete = mysql_query($query_delete, $hos) or die(mysql_error());

		//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_payable where id =\'".$id."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

	if($rs_delete){
    echo "<script>parent.$.fn.colorbox.close();parent.accrued_load('".$_POST['hn']."');</script>";
    exit();
        
	}

}
mysql_select_db($database_hos, $hos);
$query_rs_drug = "SELECT icode,name,strength FROM s_drugitems WHERE istatus='Y' and name not like '%คิด%' and name not like '%ต่อ%' and icode like '1%' ORDER BY name ASC";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);

mysql_select_db($database_hos, $hos);
$query_rs_drugitems = "SELECT o.hn,concat(name,' ',strength) as drugname,qty,d.shortlist,o.drugusage,o.icode,sp.sp_use,sp.sp_name,sp.name1 as sp_name1,sp.name2 as sp_name2,sp.name3 as sp_name3,d.name1,d.name2,d.name3,o.hn,o.vstdate FROM opitemrece o left outer join  s_drugitems s on s.icode=o.icode left outer join drugusage d on d.drugusage=o.drugusage left outer join sp_use sp on sp.sp_use=o.sp_use WHERE o.icode ='".$_GET['icode']."' and vn='".$_GET['vn']."'";
$rs_drugitems = mysql_query($query_rs_drugitems, $hos) or die(mysql_error());
$row_rs_drugitems = mysql_fetch_assoc($rs_drugitems);
$totalRows_rs_drugitems = mysql_num_rows($rs_drugitems);


mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

mysql_select_db($database_hos, $hos);
$query_rs_doctor = "SELECT d.name,o.doctorcode FROM ".$database_kohrx.".kohrx_rx_person o left outer join doctor d on d.code=o.doctorcode ";
$rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
$row_rs_doctor = mysql_fetch_assoc($rs_doctor);
$totalRows_rs_doctor = mysql_num_rows($rs_doctor);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>
<?php include('include/datepicker/datepicker.php'); ?>

<script>
jQuery(function($){ 
  $("#date1").mask("99/99/9999"); 
  $("#time1").mask("99:99");
  $("#time2").mask("99:99");
  $("#time3").mask("99:99");
  });
$(document).ready(function(){
    		set_cal( $("#date2") );
});
</script>
<script type="text/javascript">
function formSubmit(sID,displayDiv,indicator,eID) {
	if(sID!=''){ $('#do').val(sID);}
	if(eID!=''){ $('#id').val(eID);}
	 var URL = "dispen_list.php"; 
	var data = getFormData("form1");
	ajaxLoad('post', URL, data, displayDiv,indicator);
	var e = document.getElementById(indicator);
	e.style.display = 'block';
	$('#button').val("ค้นหา"); 	
	document.getElementById('button').onclick=function(){formSubmit('search','displayDiv','indicator');};			
	}
</script>
</head>

<body>
<nav class="navbar bg-info text-white">
  <!-- Navbar content -->
  <span class="card-title" style="padding-top:5px;">
<i class="fas fa-thumbs-up font20"></i>&ensp;ระบบบันทึกยาค้างจ่าย
</span>
</nav>


<form id="form1" name="form1" method="post" action="payable.php">
<div class="container mt-2">
<div class="form-row">
<div class="col-sm-3"><label class="col-form-label" for="date1">วันที่</label></div>
<div class="col-sm-9">
<input readonly="readonly" class="form-control" name="date1" type="text" id="date1" value="<? if($id!=""){ echo $esdate1; } else { echo date('d/m/').(date('Y')+543); } ?>" size="10" />
      <input name="hn" type="hidden" id="hn" value="<?php if($totalRows_rs_edit<>0){ echo $row_rs_edit['hn']; } else{ echo $row_rs_drugitems['hn']; } ?>" />
      <input name="id" type="hidden" id="id" value="<?php echo $_GET['id']; ?>" />
      <input name="vstdate" type="hidden" id="vstdate" value="<?php echo $row_rs_drugitems['vstdate']; ?>" />
</div>
</div>
<div class="form-row mt-2">
<div class="col-sm-3"><label class="col-form-label" for="drugname">รายการยา</label></div>
<div class="col-sm-9"><select name="drugname" id="drugname" class="form-control" onchange="drugtoicode(this.value)" onkeydown="setNextFocus('among');">
          <option value="">-</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rs_drug['icode']?>" <?php if (!(strcmp($row_rs_drug['icode'], $icode))) {echo "selected=\"selected\"";} ?>><?php echo  $row_rs_drug['name']." ".$row_rs_drug['strength'];
?></option>
          <?php
} while ($row_rs_drug = mysql_fetch_assoc($rs_drug));
  $rows = mysql_num_rows($rs_drug);
  if($rows > 0) {
      mysql_data_seek($rs_drug, 0);
	  $row_rs_drug = mysql_fetch_assoc($rs_drug);
  }
?>
      </select></div>
</div>
<div class="form-row mt-2">
<div class="col-sm-3"><label class="col-form-label" for="qty">จำนวนที่ค้าง</label></div>
<div class="col-sm-9"><input name="qty" type="text" id="qty" value="<?php if($id!=""){echo $row_rs_edit['among']; }else {echo $row_rs_drugitems['qty'];} ?>" class="form-control" /></div>
</div>
<div class="form-row mt-2">
<div class="col-sm-5"><label class="col-form-label" for="remark">หมายเหตุ/เหตุผลการค้าง/เบอร์โทรติดต่อ</label></div>
<div class="col-sm-7"><textarea name="remark" id="remark" class="form-control"><?php echo $row_rs_edit['remark']; ?></textarea></div>
</div>

<div class="form-row mt-2">
<div class="col-sm-3"><label class="col-form-label" for="date2">วันที่นัดรับยา</label></div>
<div class="col-sm-9"><input name="date2" type="text" id="date2" class="form-control" value="<? if($id!=""){ echo $esdate2; } else { echo date('d/m/').(date('Y')+543); } ?>" /></div>
</div>

<div class="form-row mt-2">
<div class="col-sm-3"><label class="col-form-label" for="doctor">ผู้บันทึก</label></div>
<div class="col-sm-9"><select class="form-control" name="doctor" id="doctor">
        <?php
do {  
?>
        <option value="<?php echo $row_rs_doctor['doctorcode']?>"<?php if($row_rs_edit['doctor']!=""){if (!(strcmp($row_rs_doctor['doctorcode'], $row_rs_edit['doctor']))) {echo "selected=\"selected\"";} } else {if (!(strcmp($row_rs_doctor['doctorcode'], $_SESSION['doctorcode']))) {echo "selected=\"selected\"";}} ?>><?php echo $row_rs_doctor['name']?></option>
        <?php
} while ($row_rs_doctor = mysql_fetch_assoc($rs_doctor));
  $rows = mysql_num_rows($rs_doctor);
  if($rows > 0) {
      mysql_data_seek($rs_doctor, 0);
	  $row_rs_doctor = mysql_fetch_assoc($rs_doctor);
  }
?>
      </select></div>
</div>
<div class="row mt-2">
<div class="col-sm-3"></div>
<div class="col-sm-9">
  <div class="custom-control custom-switch">
<input name="confirm" type="checkbox" class="custom-control-input" id="confirm" value="Y"  <?php if($row_rs_edit['date_pay']!=""){ echo "checked=\"checked\""; } ?> />    
    <label class="custom-control-label" for="confirm">Confirm จ่ายยาครบเรียบร้อยแล้ว</label>
  </div>

</div>

<div class="row mt-2">
<div class="col-sm-3"></div>
<div class="col-sm-9">
<nobr><input type="submit" name="button" id="button" class="btn btn-primary" value="<?php if($id!=""){echo "แก้ไข";}else{echo "บันทึก"; } ?>" />
        &nbsp; 
        <?php if($id!=""){?><input type="submit" name="button2" class="btn btn-danger" id="button2" value="ลบข้อมูล" /><? } ?>
        </nobr>
</div>

</div>
</div>
</form>
</body>
</html>
<?php
mysql_free_result($rs_drug);

mysql_free_result($rs_drugitems);

mysql_free_result($rs_doctor);

mysql_free_result($rs_setting);

?>
