<?php ob_start();?>
<?php session_start();?>

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
    if(isset($_GET['remark'])){ $remark=$_GET['remark']; }

    if(isset($_POST['remark'])){ $remark=$_POST['remark']; }

	if($_POST['qty']!=$_POST['qty_rcv']){
	if($_POST['refuse_check']!=5){
		$remark="";
		}
        
	//ค้นหาการทำรายการ
	mysql_select_db($database_hos, $hos);
	$query_drug_refuse = "select * from ".$database_kohrx.".kohrx_drug_refuse where icode='".$_POST['icode']."' and vn='".$_POST['vn']."'";
	$drug_refuse = mysql_query($query_drug_refuse, $hos) or die(mysql_error());
	$row_drug_refuse = mysql_fetch_assoc($drug_refuse);
	$totalRows_drug_refuse = mysql_num_rows($drug_refuse);

	if($totalRows_drug_refuse==0){
	mysql_select_db($database_db2, $hos);
	$query_rs_drug = "insert into ".$database_kohrx.".kohrx_drug_refuse (icode,qty,qty_rcv,vn,hn,record_date,recorder,refuse_check,remark) value ('".$_POST['icode']."','".$_POST['qty']."','".$_POST['qty_rcv']."','".$_POST['vn']."','".$_POST['hn']."',NOW(),'".$_POST['doctor']."','".$_POST['refuse_check']."','".$remark."')";

	$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
	
			//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drug_refuse (icode,qty,qty_rcv,vn,hn,record_date,recorder,refuse_check,remark) value (\'".$_POST['icode']."\',\'".$_POST['qty']."\',\'".$_POST['qty_rcv']."\',\'".$_POST['vn']."\',\'".$_POST['hn']."\',NOW(),\'".$_POST['doctor']."\',\'".$_POST['refuse_check']."\',\'".$remark."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

		}
	else{
		
	mysql_select_db($database_db2, $hos);
	$query_rs_drug = "update ".$database_kohrx.".kohrx_drug_refuse set qty='".$_POST['qty']."',qty_rcv= '".$_POST['qty_rcv']."',hn='".$_POST['hn']."',record_date=NOW(),recorder='".$_POST['doctor']."',refuse_check='".$_POST['refuse_check']."',remark='".$remark."' where vn='".$_POST['vn']."' and icode='".$_POST['icode']."'";
	$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
	
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_drug_refuse set qty=\'".$_POST['qty']."\',qty_rcv= \'".$_POST['qty_rcv']."\',hn=\'".$_POST['hn']."\',record_date=NOW(),recorder=\'".$_POST['doctor']."\',refuse_check=\'".$_POST['refuse_check']."\',remark=\'".$remark."\' where vn=\'".$_POST['vn']."\' and icode=\'".$_POST['icode']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	
		}
	}
	
    echo 
        "<script>parent.$.fn.colorbox.close();parent.refuse_load('".$_POST['hn']."','".$_POST['vstdate']."');</script>";
		exit();
       
 

	}
mysql_select_db($database_hos, $hos);
$query_drug_refuse = "select concat(d.name,' ',d.strength) as drugname,o.qty,u.shortlist from opitemrece o left outer join drugitems d on o.icode=d.icode left outer join drugusage u on u.drugusage=o.drugusage where o.icode='".$_GET['icode']."' and o.hos_guid='".$_GET['hos_guid']."' and o.vn='".$_GET['vn']."'";
$drug_refuse = mysql_query($query_drug_refuse, $hos) or die(mysql_error());
$row_drug_refuse = mysql_fetch_assoc($drug_refuse);
$totalRows_drug_refuse = mysql_num_rows($drug_refuse);

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
<script>
$(document).ready(function() {
	$('#remark_div').hide()
	
    $('#refuse_check').change(function(){
		if($('#refuse_check').val()==5){
			$('#remark_div').show()
			}
		else{
			$('#remark_div').hide()	
			$('#remark').val("");		
			}
		});
});
</script>
<style>
html,body{overflow:hidden; background-color:#E0F8FE;}
</style>
</head>

<body>
<nav class="navbar navbar-dark bg-info text-white fixed-top">
  <!-- Navbar content -->
    <span class="font18"><i class="fas fa-user-times font20"></i>&ensp;ปฏิเสธรับยา/เจ้าหน้าที่พิจารณาลดยา/งดจ่าย</span>
</nav>
<form id="form1" name="form1" method="post" action="drug_refuse.php">
<div class=" p-2" style="margin-top:50px;">
<div>
<span class="big_black16"><?php echo $row_drug_refuse['drugname']; ?></span>
        <input name="vn" type="hidden" id="vn" value="<?php echo $vn; ?>" />
        <input name="icode" type="hidden" id="icode" value="<?php echo $_GET['icode']; ?>" />
        <input name="hn" type="hidden" id="hn" value="<?php echo $_GET['hn']; ?>" />
        <input name="qty_rcv" type="hidden" id="qty_rcv" value="<?php echo $_GET['qty']; ?>" />
        <input name="vstdate" type="hidden" id="vstdate" value="<?php echo date_db2th($_GET['vstdate']); ?>" />
</div>
<div class="mt-1">
<span class="head_small_gray">วิธีใช้ :&nbsp; </span><span class="table_head_small_bord"><?php echo $row_drug_refuse['shortlist']; ?></span><span class="head_small_gray">
</div>
<div class="mt-1">
จำนวน : <span class="table_head_small_bord"><?php echo $row_drug_refuse['qty']; ?></span>
</div>
<div class="form-row mt-2">
<div class="col-sm-2 text-right">
เหตุผล :
</div>
<div class="col-sm-9">
<select id="refuse_check" name="refuse_check" class="form-control font14 text-dark">
    <option value="1">ปฏิเสธโดยผู้ป่วยเนื่องจากผู้ป่วยมียาเหลือ</option>
    <option value="2">ปฏิเสธโดยผู้ป่วยเนื่องจากไม่มีอาการ/ข้อบ่งใช้         &nbsp; ไม่มีความจำเป็นต้องใช้</option>
    <option value="3">ปฏิเสธโดยเภสัชกร/เจ้าหน้าที่ เนื่องจากพิจารณาแล้วว่าผู้ป่วยมียาเหลือ หรือไม่จำเป็นต้องใช้</option>
    <option value="4">ปฏิเสธเนื่องจากเกิดอาการแพ้หรือเกิดผลข้างเคียงจากยา</option>
    <option value="5">อื่นๆ</option>
</select>
</div>
</div>
<div class="form-row font14 mt-2" id="remark_div">
<div class="col-sm-2 text-right">
โปรดระบุ</div>
 <div class="col-sm-9">
          <input name="remark" class="form-control" type="text" id="remark" size="30" />
  </div>        
</div>

</div>
<div class=" p-2 text-center bg-info text-white position-absolute" style="bottom:0px; width:100%">
<div class="form-row">
<div class="col-sm-auto">
<label for="qty" class="col-form-label">
จำนวนได้รับจริง
</label>
</div>
<div class="col-sm-auto">
        <input class="form-control form-control-sm font14" name="qty" type="text" id="qty" value="<?php echo $row_drug_refuse['qty']; ?>" style="width:30px; padding:3px;" /></div>
<div class="col-sm-auto"><label for="doctor" class="col-form-label">ผู้บันทึก</label></div>
<div class="col-sm-auto">
        <select name="doctor" id="doctor" class="form-control form-control-sm font12">
          <?php
do {  
?>
          <option value="<?php echo $row_rs_doctor['doctorcode']?>"<?php if($row_rs_rx_operator['print_staff']!=""){ if (!(strcmp($row_rs_doctor['doctorcode'],$row_rs_rx_operator['print_staff']))) {echo "selected=\"selected\"";}} else { if (!(strcmp($row_rs_doctor['doctorcode'],$_SESSION['doctorcode']))) {echo "selected=\"selected\"";}}  ?> <?php if($row_rs_rx_operator['print_staff']!=""){ echo "style=\"background-color:#FC0\""; } ?>><?php echo $row_rs_doctor['name']?></option>
          <?php
} while ($row_rs_doctor = mysql_fetch_assoc($rs_doctor));
  $rows = mysql_num_rows($rs_doctor);
  if($rows > 0) {
      mysql_data_seek($rs_doctor, 0);
	  $row_rs_doctor = mysql_fetch_assoc($rs_doctor);
  }
?>
        </select></div>
      <div class="col-sm-auto">
      <input class="btn btn-dark btn-sm" type="submit" name="button" id="button" value="บันทึก" />
</div>      
</div></div>
</form>
</body>
</html>
<?php
mysql_free_result($drug_refuse);
mysql_free_result($rs_setting);
mysql_free_result($rs_doctor);
?>
