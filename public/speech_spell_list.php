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
if(isset($_GET['action'])&&($_GET['action']=="save")){

mysql_select_db($database_hos, $hos);
$insert = "insert into ".$database_kohrx.".kohrx_queue_patient_name_spell (name,spell) value ('".$_GET['ptname']."','".$_GET['spell']."')";
$rs_insert = mysql_query($insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_queue_patient_name_spell (name,spell) value (\'".$_GET['ptname']."\',\'".$_GET['spell']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
}

if(isset($_GET['action'])&&($_GET['action']=="edit")){

mysql_select_db($database_hos, $hos);
$update = "update  ".$database_kohrx.".kohrx_queue_patient_name_spell set spell='".$_GET['spell']."' where name='".$_GET['ptname']."'";
$rs_update = mysql_query($update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update  ".$database_kohrx.".kohrx_queue_patient_name_spell set spell=\'".$_GET['spell']."\' where name=\'".$_GET['ptname']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	
}

if(isset($_GET['action'])&&($_GET['action']=="delete")){
mysql_select_db($database_hos, $hos);
$delete = "delete from  ".$database_kohrx.".kohrx_queue_patient_name_spell where name='".$_GET['ptname']."'";
$rs_delete = mysql_query($delete, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from  ".$database_kohrx.".kohrx_queue_patient_name_spell where name=\'".$_GET['ptname']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
}

mysql_select_db($database_hos, $hos);
$query_rs_patient = "select distinct(".$_GET['type'].") as name from patient where ".$_GET['type']." like '%".$_GET['search']."%' order by ".$_GET['type']." ASC LIMIT 100";
$rs_patient = mysql_query($query_rs_patient, $hos) or die(mysql_error());
$row_rs_patient = mysql_fetch_assoc($rs_patient);
$totalRows_rs_patient = mysql_num_rows($rs_patient);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<div class="card" style="margin-right:-15px;">
<div class="card-body p-0">
<?php if($totalRows_rs_patient<>0){ ?>

<div>
<table style="width:100%" border="0" cellspacing="0" class="table-striped table-sm table table-hover">
<thead class="text-center font14">
  <tr >
    <th width="20%" height="35px;" style="border-top:0px; border-bottom:1px solid #CCC" align="center">ลำดับ</th>
    <th width="30%" style="border-top:0px;border-bottom:1px solid #CCC"align="center"><?php if($_GET['type']=="fname"){ echo "ชื่อผู้ป่วย"; } else{ echo "นามสกุลผู้ป่วย";}?></th>
    <th width="30%" style="border-top:0px;border-bottom:1px solid #CCC" align="center">การสะกด</th>
    <th width="20%" style="border-top:0px;border-bottom:1px solid #CCC" align="center">แก้ไข</th>
  </tr>
</thead>
</table>
</div>
<div style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:330px; margin-top:-17px;">
<table width="414" border="0" cellpadding="3" cellspacing="0" class="table-striped table-sm table table-hover">
<tbody>
  <?php $i=0; do { $i++; 
mysql_select_db($database_hos, $hos);
$query_rs_spell = "select * from ".$database_kohrx.".kohrx_queue_patient_name_spell where name='".$row_rs_patient['name']."'";
$rs_spell = mysql_query($query_rs_spell, $hos) or die(mysql_error());
$row_rs_spell = mysql_fetch_assoc($rs_spell);
$totalRows_rs_spell = mysql_num_rows($rs_spell);  
  ?>
  <tr class="grid">
    <td width="20%" align="center" valign="middle"><?php echo $i; ?></td>
    <td width="30%" align="center" valign="middle"><?php echo $row_rs_patient['name']."</br>"; ?></td>
    <td width="30%" align="center" valign="middle"><?php echo $row_rs_spell['spell']; ?></td>
    <td width="20%" align="center" valign="middle"><a href="javascript:valid();" onclick="speech_edit('<?php echo $_GET['type']; ?>','<?php echo $row_rs_patient['name']; ?>')" id="fancybox5"><i class="fas fa-keyboard text-dark font20"></i></a> <a href="queue.php?words=<?php echo $row_rs_patient['name']; ?>" id="fancybox2"><i class="fas fa-headphones text-dark font20"></i></a></td>
  </tr>
  <?php } while ($row_rs_patient = mysql_fetch_assoc($rs_patient)); ?>
  </tbody>
</table>
</div>
<?php
mysql_free_result($rs_spell);
 } else { echo "<div class=\"text-center p-3\">ไม่พบข้อมูล</div>";}?>
 </div>
</div>
</body>
</html>
<?php
mysql_free_result($rs_patient);


?>
