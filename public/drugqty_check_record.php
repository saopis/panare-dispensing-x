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

 if(isset($_POST['button'])&&($_POST['button']=="บันทึก")){
	mysql_select_db($database_hos, $hos);
	$insert = "insert into  ".$database_kohrx.".kohrx_drugqty_check_record (vn,hn,icode,drugusage,appdate,qty,qtyideal,doctorcode,daterecord) value ('".$_POST['vn']."','".$_POST['hn']."','".$_POST['icode']."','".$_POST['drugusage']."','".$_POST['appdate']."','".$_POST['qty']."','".$_POST['qtyideal']."','".$_POST['doctor']."',NOW())";
	$qinsert = mysql_query($insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into  ".$database_kohrx.".kohrx_drugqty_check_record (vn,hn,icode,drugusage,appdate,qty,qtyideal,doctorcode,daterecord) value (\'".$_POST['vn']."\',\'".$_POST['hn']."\',\'".$_POST['icode']."\',\'".$_POST['drugusage']."\',\'".$_POST['appdate']."\',\'".$_POST['qty']."\',\'".$_POST['qtyideal']."\',\'".$_POST['doctor']."\',NOW())')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

	} 
	if($qinsert){
	echo "<script>parent.drug_list_load('".$_POST['hn']."','".$_POST['vstdate']."','".$_POST['vn']."','".$_POST['pdx']."','".$_POST['dx0']."','".$_POST['dx1']."','".$_POST['dx2']."','".$_POST['dx3']."','".$_POST['dx4']."','".$_POST['dx5']."','".$_POST['age_y']."','".$_POST['date_diff']."');parent.$.fn.colorbox.close();</script>";
	}

	?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>    
</head>

<body>
<nav class="navbar navbar-dark bg-danger text-white ">
  <!-- Navbar content -->
    <span class="font18"><i class="fas fa-user-times font20"></i>&ensp;บันทึกความคลาดเคลื่อนทางยาในการสั่งยาผิดจำนวน</span>
</nav>
<div class="p-2">
<form id="form1" name="form1" method="post" action="">
  <span class="big_red16"><?php echo $drugname; ?>
  &nbsp;</span> <?php echo $drugusage; ?>&nbsp; # <?php echo $qty; ?><br />
  นัด<span class="small_red_bord">&nbsp; <?php echo $appdate; ?> </span>วัน
  &nbsp;จำนวนที่ควรสั่ง<span class="small_red_bord"> &nbsp; <?php echo $qtyideal; ?></span> เม็ด<br />
	<div class="text-center mt-5"> 
  <input type="submit" name="button" id="button" value="บันทึก" class="btn btn-danger" />
 </div>
  <input name="vn" type="hidden" id="vn" value="<?php echo $_GET['vn']; ?>" />
  <input name="icode" type="hidden" id="icode" value="<?php echo $_GET['icode']; ?>" />
  <input name="qty" type="hidden" id="qty" value="<?php echo $_GET['qty']; ?>" />
  <input name="qtyideal" type="hidden" id="qtyideal" value="<?php echo $_GET['qtyideal']; ?>" />
  <input name="doctor" type="hidden" id="doctor" value="<?php echo $_GET['doctor']; ?>" />
  <input name="drugusage" type="hidden" id="drugusage" value="<?php echo $_GET['drugusage']; ?>" />
  <input name="appdate" type="hidden" id="appdate" value="<?php echo $_GET['appdate']; ?>" />
  <input name="vstdate" type="hidden" id="vstdate" value="<?php echo $_GET['vstdate']; ?>" />
  <input name="pdx" type="hidden" id="pdx" value="<?php echo $_GET['pdx']; ?>" />
  <input name="hn" type="hidden" id="hn" value="<?php echo $_GET['hn']; ?>" />
  <input name="dx0" type="hidden" id="dx0" value="<?php echo $_GET['dx0']; ?>" />
  <input name="dx1" type="hidden" id="dx1" value="<?php echo $_GET['dx1']; ?>" />
  <input name="dx2" type="hidden" id="dx2" value="<?php echo $_GET['dx2']; ?>" />
  <input name="dx3" type="hidden" id="dx3" value="<?php echo $_GET['dx3']; ?>" />
  <input name="dx4" type="hidden" id="dx4" value="<?php echo $_GET['dx4']; ?>" />
  <input name="dx5" type="hidden" id="dx5" value="<?php echo $_GET['dx5']; ?>" />
  <input name="age_y" type="hidden" id="age_y" value="<?php echo $_GET['age_y']; ?>" />
  <input name="date_diff" type="hidden" id="date_diff" value="<?php echo $_GET['date_diff']; ?>" />

</form>
	</div>
</body>
</html>