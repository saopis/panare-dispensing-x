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
$date11=explode("-",date('Y-m-d'));
$today=($date11[2]."/".$date11[1]."/".($date11[0]+543));

if(isset($_GET['action'])&&($_GET['id']!="")){
mysql_select_db($database_hos, $hos);
$query_rs_inform_edit = "select * from ".$database_kohrx.".kohrx_information_service where id='$id'" ;
$rs_inform_edit = mysql_query($query_rs_inform_edit, $hos) or die(mysql_error());
$row_rs_inform_edit = mysql_fetch_assoc($rs_inform_edit);
$totalRows_rs_inform_edit = mysql_num_rows($rs_inform_edit);

$date11=explode("-",$row_rs_inform_edit['expire_date']);
$edate2=($date11[2]."/".$date11[1]."/".($date11[0]+543));

}
if(isset($_POST['button2'])&&($_POST['button2']=="แก้ไข")){
	$date11=explode("/",$date1);
$edate1=(($date11[2]-543)."-".$date11[1]."-".$date11[0]);

	mysql_select_db($database_hos, $hos);
	$query_update = "update ".$database_kohrx.".kohrx_information_service set  poster='$poster',information_text='$text',expire_date='$edate1' where id='$id'";
	$update = mysql_query($query_update, $hos) or die(mysql_error());

	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_information_service set poster=\'".$poster."\',information_text=\'".$text."\',expire_date=\'".$edate1."\' where id=\'".$id."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

echo "<meta http-equiv=\"refresh\" content=\"0;URL=information_marquee.php\" />";

}
if(isset($_GET['do'])&&($_GET['id']!="")){

	mysql_select_db($database_hos, $hos);
	$query_delete = "delete from ".$database_kohrx.".kohrx_information_service where id='$id'";
	$delete = mysql_query($query_delete, $hos) or die(mysql_error());

	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_information_service where id=\'".$id."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

echo "<meta http-equiv=\"refresh\" content=\"0;URL=information_marquee.php\" />";

}

if(isset($_POST['button'])&&($_POST['button']=="บันทึก")){
	
$date11=explode("/",$date1);
$edate1=(($date11[2]-543)."-".$date11[1]."-".$date11[0]);

	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into ".$database_kohrx.".kohrx_information_service (poster,information_text,post_date,expire_date) value ('$poster','$text',NOW(),'$edate1')";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());

	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_information_service (poster,information_text,post_date,expire_date) value (\'".$poster."\',\'".$text."\',NOW(),\'".$edate1."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

	}
mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

mysql_select_db($database_hos, $hos);
$query_rs_doctor = "SELECT o.name,o.doctorcode,o.hospital_department_id FROM opduser o left outer join doctor d on d.code=o.doctorcode WHERE o.hospital_department_id ='$row_setting[1]' and d.active='Y' order by name";
$rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
$row_rs_doctor = mysql_fetch_assoc($rs_doctor);
$totalRows_rs_doctor = mysql_num_rows($rs_doctor);

mysql_select_db($database_hos, $hos);
$query_rs_inform = "select i.*,d.name,DATEDIFF(CURDATE(),i.expire_date) as date_diff from ".$database_kohrx.".kohrx_information_service i left outer join doctor d on d.code=i.poster order by expire_date  DESC";
$rs_inform = mysql_query($query_rs_inform, $hos) or die(mysql_error());
$row_rs_inform = mysql_fetch_assoc($rs_inform);
$totalRows_rs_inform = mysql_num_rows($rs_inform);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<?php include('java_function.php'); ?>

</head>

<body >
<span class="big_red16">ระบบประชาสัมพันธ์ข้อความ(ตัวหนังสือวิ่ง)
</span><br />
<form id="form1" name="form1" method="post" action="">
  <textarea name="text" cols="100" rows="10" class="table_head_small" id="text"><?php echo $row_rs_inform_edit['information_text']; ?></textarea>
  <br />
ผู้ประกาศ 
<span class="table_head_small">
<input name="doctor" type="text" class="inputcss1" id="doctor"  onkeyup="resutName(this.value,'poster');" value="<?php echo $row_rs_inform_edit['poster']; ?>" size="2"  />
<select name="poster" class="inputcss1" id="poster" onchange="doctorcode(this.value,'rx_check')" onkeydown="setNextFocus('remark');" >
  <?php
do {  
?>
  <option value="<?php echo $row_rs_doctor['doctorcode']?>"<?php if (!(strcmp($row_rs_doctor['doctorcode'], $row_rs_inform_edit['poster']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_doctor['name']?></option>
  <?php
} while ($row_rs_doctor = mysql_fetch_assoc($rs_doctor));
  $rows = mysql_num_rows($rs_doctor);
  if($rows > 0) {
      mysql_data_seek($rs_doctor, 0);
	  $row_rs_doctor = mysql_fetch_assoc($rs_doctor);
  }
?>
</select>
</span> &nbsp;
<input name="id" type="hidden" id="id" value="<?php echo $id; ?>" />
<br />
วันที่หมดอายุ
<span class="head_small_gray">
<input name="date1" type="text" class="inputcss1" id="date1"  value="<? if(!isset($action)&&$id==""){ echo $today; } else { echo $edate2;}?>" size="6"  />
</span>
<?php if($action!="edit"){ ?><input type="submit" name="button" id="button" value="บันทึก" /><? } else {?>
<input type="submit" name="button2" id="button2" value="แก้ไข" /><? } ?>
</form>
<table width="1000" border="0" cellspacing="0" cellpadding="3">
  <tr class="gray">
    <td width="77" height="26" align="center" class="rounded_top_left">ลำดับ</td>
    <td width="493" align="center">ข้อความ</td>
    <td width="121" align="center">ผู้ประกาศ</td>
    <td width="130" align="center">วันที่ประกาศ</td>
    <td width="138" align="center">วันที่หมดอายุ</td>
    <td width="41" align="center" class="rounded_top_right">&nbsp;</td>
  </tr>
    <?php $i=0; do { $i++; ?>
  <tr class="table_head_small" <?php  if($row_rs_inform['date_diff']>0){ ?> style="background: #E2E2E2" <?php } ?>>
    <td align="center" valign="top" style="border-bottom:dashed 1px #999999"><?=$i; ?></td>
      <td align="left" valign="top" style="border-bottom:dashed 1px #999999"><?php echo $row_rs_inform['information_text']; ?></td>
    <td align="center" valign="top" style="border-bottom:dashed 1px #999999"><?php echo $row_rs_inform['name']; ?></td>
      <td align="center" valign="top" style="border-bottom:dashed 1px #999999"><?=$row_rs_inform['post_date']; ?></td>
      <td align="center" valign="top" style="border-bottom:dashed 1px #999999"><?=$row_rs_inform['expire_date']; ?></td>
      <td align="center" valign="top" style="border-bottom:dashed 1px #999999"><a href="information_marquee.php?action=edit&amp;id=<?php echo $row_rs_inform['id']; ?>"><img src="images/gtk-edit.png" width="16" height="16" border="0" align="absmiddle" /></a><a href="JavaScript:if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){ window.location='information_marquee.php?id=<?php echo $row_rs_inform['id']; ?>&amp;do=delete'; }"><img src="images/bin.png" width="16" height="16" border="0" align="absmiddle" /></a></td>
  </tr>      
  <?php } while ($row_rs_inform = mysql_fetch_assoc($rs_inform)); ?>

</table>

</body>
</html>
<?
mysql_free_result($rs_doctor);

mysql_free_result($rs_setting);
?>
<?php
mysql_free_result($rs_inform);
?>
