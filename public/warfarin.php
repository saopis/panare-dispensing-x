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

if(isset($_POST['button'])&&($_POST['button']=="บันทึกข้อมูล"))
	{
	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into ".$database_kohrx.".kohrx_patient_warfarin (hn,record_date) value ('$hn',CURDATE())";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_patient_warfarin (hn,record_date) value (\'".$hn."\',CURDATE())')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

	echo "<script>parent.$.fn.colorbox.close();parent.shortcut_load('".$hn."');</script>";
	exit();
	
	}

if(isset($_POST['button2'])&&($_POST['button2']=="ลบข้อมูล"))
	{
	mysql_select_db($database_hos, $hos);
	$query_delete = "delete from ".$database_kohrx.".kohrx_patient_warfarin where hn='$hn' ";
	$delete = mysql_query($query_delete, $hos) or die(mysql_error());
	
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_patient_warfarin where hn=\'".$hn."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

	echo "<script>parent.$.fn.colorbox.close();parent.shortcut_load('".$hn."');</script>";
	exit();

	}

mysql_select_db($database_hos, $hos);
$query_warfarin = "SELECT * from ".$database_kohrx.".kohrx_patient_warfarin WHERE hn='$hn'";
$warfarin = mysql_query($query_warfarin, $hos) or die(mysql_error());
$row_warfarin = mysql_fetch_assoc($warfarin);
$totalRows_warfarin = mysql_num_rows($warfarin);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<!-- jquery -->
<script src="include/jquery/js/jquery.min.js" ></script>
<!-- bootstrap -->
<link rel="stylesheet" href="include/bootstrap/css/bootstrap.min.css">
<script src="include/bootstrap/js/popper.min.js"></script>
<script src="include/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="include/bootstrap/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="include/bootstrap/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" type="text/css" href="include/bootstrap/css/jquery.dataTables.min.css"/>
</head>
<!-- fontawesome -->
<link rel="stylesheet" href="include/fontawesome/css/all.css">
<!-- kohrx -->
<link rel="stylesheet" href="include/kohrx/css/kohrx.css">
<style>
html,body{background-color:#F17288; overflow:hidden}
</style>
</head>

<body>
<nav class="navbar navbar-dark thfont bg-danger text-white">
  <!-- Navbar content -->
  <span class="card-title" style="padding-top:5px;">
<i class="fas fa-skull-crossbones">&ensp;บันทึกผู้ป่วยใช้ยา Warfarin</i>
</span>
</nav>
<div class="text-white container-fluid mt-3 text-center font18" >
<form action="warfarin.php?hn=<?php echo $hn; ?>" method="post">
  <?php if($totalRows_warfarin==0){ ?><nobr>
  ผู้ป่วยยังไม่มีข้อมูลในระบบ ต้องการบันทึกหรือไม่? 
    <input type="submit" name="button" class="btn btn-dark" id="button" value="บันทึกข้อมูล" /></nobr> <? } ?>
   
  <?php if($totalRows_warfarin<>0){ ?><nobr>ผู้ป่วยมีข้อมูลในระบบแล้ว ต้องการลบหรือไม่? 
    <input type="submit" class="btn btn-dark" name="button2" id="button2" value="ลบข้อมูล" /></nobr>
  <? } ?>
  </form>
</div>
</body>
</html>
<?php
mysql_free_result($warfarin);
?>
