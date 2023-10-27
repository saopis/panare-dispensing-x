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
?>
<?php 
if(isset($_POST['button9'])&&$_POST['button9']=="เพิ่ม"){
mysql_select_db($database_hos, $hos);
$query_insert = "insert into ".$database_kohrx.".kohrx_drugcheck (icode,doseperunit,maxdoseperuse,maxdoseperday) value ('".$_POST['drug']."','".$_POST['doseperunit']."','".$_POST['maxdoseperuse']."','".$_POST['maxdoseperday']."')";
$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drugcheck (icode,doseperunit,maxdoseperuse,maxdoseperday) value (\'".$_POST['drug']."\',\'".$_POST['doseperunit']."\',\'".$_POST['maxdoseperuse']."\',\'".$_POST['maxdoseperday']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

}
if(isset($_GET['do'])&&$_GET['do']=="delete"){
mysql_select_db($database_hos, $hos);
$query_insert = "delete from ".$database_kohrx.".kohrx_drugcheck where icode ='".$_GET['icode']."'";
$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());
}

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_drugcheck where icode =\'".$_GET['icode']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());


mysql_select_db($database_hos, $hos);
$query_rs_drug2 = "SELECT icode,concat(name,strength) as drugname FROM s_drugitems WHERE icode like '1%' and istatus='Y' and icode not in (select icode from ".$database_kohrx.".kohrx_drugcheck) ORDER BY name ASC";
$rs_drug2 = mysql_query($query_rs_drug2, $hos) or die(mysql_error());
$row_rs_drug2 = mysql_fetch_assoc($rs_drug2);
$totalRows_rs_drug2 = mysql_num_rows($rs_drug2);

mysql_select_db($database_hos, $hos);
$query_rs_sp = "select concat(s.name,' ',s.strength) as drugname,c.* from ".$database_kohrx.".kohrx_drugcheck c left outer join s_drugitems s on c.icode=s.icode";
$rs_sp = mysql_query($query_rs_sp, $hos) or die(mysql_error());
$row_rs_sp = mysql_fetch_assoc($rs_sp);
$totalRows_rs_sp = mysql_num_rows($rs_sp);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_online.php'); ?>
</head>

<body>
<div class="p-3" style="margin-top:10px;">
<div class="card">
<div class="card-header">กำหนดขนาดยา</div>
<div class="card-body">
<form action="" method="post" name="form1" class="table_head_small thfont" id="form1">
  <div class="form-group row">
  <label for="drug" class="col-sm-2 col-form-label">รายการยา</label>
  <div class="col-sm-10">
  <select name="drug" id="drug" class="thfont form-control">
    <?php
do {  
?>
    <option value="<?php echo $row_rs_drug2['icode']?>"><?php echo $row_rs_drug2['drugname']?></option>
    <?php
} while ($row_rs_drug2 = mysql_fetch_assoc($rs_drug2));
  $rows = mysql_num_rows($rs_drug2);
  if($rows > 0) {
      mysql_data_seek($rs_drug2, 0);
	  $row_rs_drug2 = mysql_fetch_assoc($rs_drug2);
  }
?>
  </select>
	</div>
    </div>
   <div class="form-row">
   <div class="col-md-3 mb-3">
	   <label for="doseperunit" >ขนาดยาต่อหน่วย</label>
  <input name="doseperunit" type="text" id="doseperunit" class=" form-control" placeholder="mg." />
 	</div>
   <div class="col-md-3 mb-3">ุ
	<label for="maxdoseperuse" >ขนาดยาสูงสุดต่อครั้ง</label>
  <input name="maxdoseperuse" type="text" id="maxdoseperuse" class="form-control" placeholder="mg." /> 
  </div>
  <div class="col-md-3 mb-3">ุุ
   <label for="maxdoseperday" >ขนาดยาสูงสุดต่อวัน</label>
  <input name="maxdoseperday" type="text" id="maxdoseperday"  class="form-control" placeholder="mg." /> 
  </div>
 <div class=" col-md-3 mb-3" style="margin-top:30px;">ุุ
  <input name="button9" type="submit" class="btn btn-primary" id="button9" value="เพิ่ม" />
  </div>
  </div>
</form>

</div>
</div>
<div style="margin-top:10px;">
<table width="100%" border="0" cellpadding="3" cellspacing="0"  id="table" class="table table-striped table-bordered" style="width:100%">
	<thead>
    <tr>
    <td width="40" align="center" >ลำดับ</td>
    <td width="347" align="center" >ชื่อยา</td>
    <td width="95" align="center" >ขนาด/หน่วย(mg.)</td>
    <td width="84" align="center" >max/ครั้ง(mg.)</td>
    <td width="67" align="center" >max/day(mg.)</td>
    <td width="31" align="center" >&nbsp;</td>
  </tr>
  </thead>
  <tbody>
   <?php $i=0; do { $i++; 
   ?><tr>
   
      <td align="center" ><?php echo $i; ?></td>
      <td align="center" ><?php echo "$row_rs_sp[drugname]"; ?></td>
      <td align="center" ><?php echo "$row_rs_sp[doseperunit]"; ?></td>
      <td align="center" ><?php echo "$row_rs_sp[maxdoseperuse]"; ?></td>
      <td align="center" ><?php echo "$row_rs_sp[maxdoseperday]"; ?></td>
      <td align="center" ><i class="fas fa-trash" onclick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){window.location='drug_setting_dosing.php?do=delete&amp;icode=<?php echo $row_rs_sp["icode"]; ?>';}" style="cursor:pointer;"></i></td>
     
  </tr> <?php } while ($row_rs_sp = mysql_fetch_assoc($rs_sp)); ?>
  </tbody>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($rs_sp);
?>
