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
//php5.5
$icode=$_GET['icode'];
$hn=$_GET['hn'];
$id=$_GET['id'];
////
if($_GET['id']!=""){
mysql_select_db($database_hos, $hos);
$query_rs_edit = "select * from ".$database_kohrx.".kohrx_couselling where id='".$_GET['id']."'";
$rs_edit = mysql_query($query_rs_edit, $hos) or die(mysql_error());
$row_rs_edit = mysql_fetch_assoc($rs_edit);
$totalRows_rs_edit = mysql_num_rows($rs_edit);
$icode=$row_rs_edit['icode'];

$date11=explode("-",$row_rs_edit['record_date']);
$edate2=($date11[2]."/".$date11[1]."/".($date11[0]+543));
$hn=$row_rs_edit['hn'];
$patient_type=$row_rs_edit['patient_type'];
}
else{
$patient_type= $_GET['patient_type'];   
}

if(isset($_POST['edit'])&&($_POST['edit']=="แก้ไข")){
$date11=explode("/",$_POST['date1']);
$edate1=(($date11[2]-543)."-".$date11[1]."-".$date11[0]);

mysql_select_db($database_hos, $hos);
$insert = "update ".$database_kohrx.".kohrx_couselling set record_date='".$edate1."',patient_type='".$_POST['patient_type']."',icode='".$_POST['drugname']."',patient='".$_POST['patient']."',result='".$_POST['result']."',problem='".$_POST['problem']."',note='".$_POST['note']."',couseller='".$_POST['couseller']."',recorder='".$_POST['recorder']."',other='".$_POST['patient_other']."',record_time='".$_POST['time1']."' where id='".$_POST['id']."'";
$rs_insert = mysql_query($insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_couselling set record_date=\'".$edate1."\',patient_type=\'".$_POST['patient_type']."\',icode=\'".$_POST['drugname']."\',patient=\'".$_POST['patient']."\',result=\'".$_POST['result']."\',problem=\'".$_POST['problem']."\',note=\'".$_POST['note']."\',couseller=\'".$_POST['couseller']."\',recorder=\'".$_POST['recorder']."\',other=\'".$_POST['patient_other']."\',record_time=\'".$_POST['time1']."\' where id=\'".$_POST['id']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());


if($_SESSION['pt']=="OPD"){
    echo 
        "<script>parent.$.fn.colorbox.close();parent.counseling_load('".$_POST['hn']."');</script>";
}
else if($_SESSION['pt']=="IPD"){
echo "<script>parent.$.fn.colorbox.close();parent.counselling_load_list();</script>";
}
    exit();
}

if(isset($_POST['delete'])&&($_POST['delete']=="ลบ")){
mysql_select_db($database_hos, $hos);
$delete = "delete from ".$database_kohrx.".kohrx_couselling where id='".$_POST['id']."'";
$sdelete = mysql_query($delete, $hos) or die(mysql_error());
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from couselling where id=\'".$_POST['id']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());



if($_SESSION['pt']=="OPD"){
    echo 
        "<script>parent.$.fn.colorbox.close();parent.counseling_load('".$_POST['hn']."');</script>";
}
else if($_SESSION['pt']=="IPD"){
echo "<script>parent.$.fn.colorbox.close();parent.counselling_load_list();</script>";
}
exit();

}

if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){
    
mysql_select_db($database_hos, $hos);
$insert = "insert into ".$database_kohrx.".kohrx_couselling (hn,record_date,record_time,patient_type,icode,patient,result,problem,note,couseller,recorder,other)";
$insert.=" value('".$_POST['hn']."','".date_th2db($_POST['date1'])."','".$_POST['time1']."','".$_POST['patient_type']."','".$_POST['drugname']."','".$_POST['patient']."','".$_POST['result']."','".$_POST['problem']."','".$_POST['note']."','".$_POST['couseller']."','".$_POST['recorder']."','".$_POST['patient_other']."')";
$rs_insert = mysql_query($insert, $hos) or die(mysql_error());
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_couselling (hn,record_date,record_time,patient_type,icode,patient,result,problem,note,couseller,other) value(\'".$_POST['hn']."\',\'".date_th2db($_POST['date1'])."\',\'".$_POST['time1']."\',\'".$_POST['patient_type']."\',\'".$_POST['drugname']."\',\'".$_POST['patient']."\',\'".$_POST['result']."\',\'".$_POST['problem']."\',\'".$_POST['note']."\',\'".$_POST['couseller']."\',\'".$_POST['patient_other']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

if($_SESSION['pt']=="OPD"){
    echo 
        "<script>parent.$.fn.colorbox.close();parent.counseling_load('".$_POST['hn']."');</script>";
}
else if($_SESSION['pt']=="IPD"){
echo "<script>parent.$.fn.colorbox.close();parent.counselling_load_list();</script>";
}
exit();
}

mysql_select_db($database_hos, $hos);
$query_rs_drug = "SELECT icode,name,strength FROM s_drugitems WHERE istatus='Y' and name not like '%คิด%' and name not like '%ต่อ%' and icode like '1%' ORDER BY name ASC";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);

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
<!-- datepicker -->
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
<?php include('java_css_online.php'); ?>
<?php include('include/datepicker/datepicker.php'); ?>

<script>
	$(document).ready(function($){ 

    set_cal( $("#date1") );

	});
</script>

</head>

<body>
<nav class="navbar navbar-dark bg-info text-white " style="margin-top:0px;">
  <!-- Navbar content -->
    <h4><i class="fas fa-comment-medical" style="font-size:20px;"></i>&nbsp;แบบฟอร์มบันทึกให้คำปรึกษาการใช้ยาเทคนิคพิเศษ</h4>
</nav>    

<form id="form1" name="form1" method="post" action="form_couselling.php">
<div class="container-fluid" style="padding:10px; ">
<div class="form-row">
	<div class="col-sm-2"><label for="date1" class="col-form-label">วันเดือนปี</label></div>
	<div class="col-sm-2"><input name="date1" type="text" class="form-control" id="date1" value="<? if(!isset($_POST['edate2'])){ echo date('d/m/').(date('Y')+543); } else { echo $edate2; } ?>" /></div>
	<div class="col-sm-auto"><label for="time1" class="col-form-label">เวลา</label></div>
	<div class="col-sm-2"><input name="time1" id="time1" class="form-control" value="<?php if($row_rs_edit['record_time']!=""){ echo $row_rs_edit['record_time']; } else { echo date('H:m:s'); } ?>"/></div>
	<div class="col-sm-auto"><label for="patient_type" class="col-form-label">ประเภทผู้ป่วย</label></div>
	<div class="col">        <select name="patient_type" id="patient_type" class="form-control">
          <option value="OPD" <?php if (!(strcmp("OPD", $patient_type))) {echo "selected=\"selected\"";} ?>>OPD</option>
          <option value="IPD" <?php if (!(strcmp("IPD", $patient_type))) {echo "selected=\"selected\"";} ?>>IPD</option>
        </select>
        <input type="hidden" name="do" id="do" />
        <input name="id" type="hidden" id="id" value="<?php echo $id; ?>" />
        <a name="edit" id="edit"></a>
        <input name="hn" type="hidden" id="hn" value="<?php echo $hn; ?>" />
        <input name="vstdate" type="hidden" id="vstdate" value="<?php echo $_GET['vstdate']; ?>" />
</div>
    
</div>
<div class="form-row" style="margin-top:5px;">
<div class="col-sm-2">ยาที่ให้คำแนะนำ</div>
<div class="col">    
<select name="drugname" id="drugname" onchange="drugtoicode(this.value)" onkeydown="setNextFocus('among');" class="form-control">
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
          </select>
      </div>
</div>
<div class="form-row" style="margin-top:5px;">
<div class="col-sm-2"><label for="date1" class="col-form-label">ผู้รับคำแนะนำ</label></div>
<div class="col"><select name="patient" id="patient" class="form-control">
          <option value="1" <?php if (!(strcmp(1, $row_rs_edit['patient']))) {echo "selected=\"selected\"";} ?>>ผู้ป่วย</option>
          <option value="2" <?php if (!(strcmp(2, $row_rs_edit['patient']))) {echo "selected=\"selected\"";} ?>>ญาติผู้ป่วย/คนอื่น</option>
        </select></div>
<div class="col-sm-auto"><label for="date1" class="col-form-label">กรณีญาติหรืออื่นๆ ระบุ</label></div>
<div class="col"><input name="patient_other" type="text" id="patient_other" class="form-control" value="<?php echo $row_rs_edit['other']; ?>" /></div>
</div>
<div class="form-row" style="margin-top:5px;">
<div class="col-sm-2"><label for="date1" class="col-form-label">ผลการประเมิน</label></div>
<div class="col"><select name="result" id="result" class="form-control">
          <option value="1" <?php if (!(strcmp(1, $row_rs_edit['result']))) {echo "selected=\"selected\"";} ?>>ปฏิบัติตามคำแนะนำได้</option>
          <option value="2" <?php if (!(strcmp(2, $row_rs_edit['result']))) {echo "selected=\"selected\"";} ?>>ปฏิบัติได้เล็กน้อย ต้องติดตามและประเมินซ้ำ</option>
          <option value="3" <?php if (!(strcmp(3, $row_rs_edit['result']))) {echo "selected=\"selected\"";} ?>>ไม่สามารถปฏิบัติตามคำแนะนำได้</option>
        </select></div>
<div class="col-sm-auto"><label for="date1" class="col-form-label">ปัญหาที่พบ</label></div>

<div class="col"><textarea name="problem" id="problem" class="form-control"><?php echo $row_rs_edit['problem']; ?></textarea></div>
</div>
<div class="form-row" style="margin-top:5px;">
<div class="col-md-2"><label for="date1" class="col-form-label">note</label></div>
<div class="col"><textarea name="note" id="note" class="form-control"><?php echo $row_rs_edit['note']; ?></textarea></div>
</div>
<div class="form-row" style="margin-top:5px;">
<div class="col-sm-2"><label for="date1" class="col-form-label">ผู้ให้คำแนะนำ</label></div>
<div class="col"><select name="couseller" id="couseller" class="form-control" >
          <?php
do {  
?>
          <option value="<?php echo $row_rs_doctor['doctorcode']?>"<?php if($row_rs_edit['couseller']!=""){ if (!(strcmp($row_rs_doctor['doctorcode'],$row_rs_edit['couseller']))) {echo "selected=\"selected\"";}} else { if (!(strcmp($row_rs_doctor['doctorcode'],$_SESSION['doctorcode']))) {echo "selected=\"selected\"";}}  ?> <?php if($row_rs_edit['couseller']!=""){ echo "style=\"background-color:#FC0\""; } ?>><?php echo $row_rs_doctor['name']?></option>
          <?php
} while ($row_rs_doctor = mysql_fetch_assoc($rs_doctor));
  $rows = mysql_num_rows($rs_doctor);
  if($rows > 0) {
      mysql_data_seek($rs_doctor, 0);
	  $row_rs_doctor = mysql_fetch_assoc($rs_doctor);
  }
?>
        </select></div>
<div class="col-sm-auto"><label for="date1" class="col-form-label">ผู้บันทึก</label></div>
<div class="col"><select name="recorder" id="recorder" class="form-control" >
          <?php
do {  
?>
          <option value="<?php echo $row_rs_doctor['doctorcode']?>"<?php if($row_rs_edit['recorder']!=""){ if (!(strcmp($row_rs_doctor['doctorcode'],$row_rs_edit['recorder']))) {echo "selected=\"selected\"";}} else { if (!(strcmp($row_rs_doctor['doctorcode'],$_SESSION['doctorcode']))) {echo "selected=\"selected\"";}}  ?> <?php if($row_rs_edit['recorder']!=""){ echo "style=\"background-color:#FC0\""; } ?>><?php echo $row_rs_doctor['name']?></option>
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
<div class="form-row" style="margin-top:10px;">
<div class="col-md-3"></div>
<div class="col">
<?php if(!isset($id)){?>
      <input type="submit" name="save" class="btn btn-primary" id="save" value="บันทึก" /><?php } else { ?><input type="submit" name="edit" id="edit" class="btn btn-primary" value="แก้ไข" />
      <input type="submit" name="delete" id="delete" value="ลบ" class="btn btn-danger" onclick="return confirm('ยืนยันการลบข้อมูล?')" />      <? } ?>
 </div>
<!-- container-fluid -->
</div>
</form>
</body>
</html>
<?php
mysql_free_result($rs_drug);

mysql_free_result($rs_doctor);

?>
