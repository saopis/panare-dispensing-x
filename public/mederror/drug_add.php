<?php require_once('../Connections/hos.php'); ?>
<? 
$stamp=$_GET['stamp'];
if($_GET['do']=="insert"){
$drugcode=explode('/',$_GET['drug']);

if($_GET['id']!=""){

mysql_select_db($database_hos, $hos);
$insert = "insert into ".$database_kohrx.".kohrx_med_error_report_drug (rid,icode,did,drug_option,stamp,d_update) value ('".$_GET['id']."','".$drugcode[0]."','".$drugcode[1]."','".$_GET['drug_option']."','".$stamp."',NOW())";
$qinsert = mysql_query($insert,$hos)  or die (mysql_error());
$condition=" rid='".$_GET['id']."'";
	}
else{
mysql_select_db($database_hos, $hos);
$insert = "insert into ".$database_kohrx.".kohrx_med_error_report_drug (icode,did,drug_option,stamp,d_update) value ('".$drugcode[0]."','".$drugcode[1]."','".$_GET['drug_option']."','".$stamp."',NOW())";
$qinsert = mysql_query($insert,$hos)  or die (mysql_error());
$condition=" stamp='".$stamp."' and (rid='' or rid is NULL)";

}
mysql_select_db($database_hos, $hos);
$rs_drug = "select * from ".$database_kohrx.".kohrx_med_error_report_drug where ".$condition;
$qdrug = mysql_query($rs_drug,$hos)  or die (mysql_error());
$row_drug = mysql_fetch_assoc($qdrug);
$totalRows = mysql_num_rows($qdrug);
	
}
//==== finish insert =====//
if($_GET['do']=="delete"){
    $condition="id='".$_GET['id']."'";
    mysql_select_db($database_hos, $hos);
    $delete = "delete from ".$database_kohrx.".kohrx_med_error_report_drug where ".$condition;
    $qdelete = mysql_query($delete,$hos)  or die (mysql_error());
    
	if($_GET['rid']==""){
    $condition=" stamp ='".$stamp."' and (rid='' or rid is NULL)";
	}
	else {
	$condition=" rid='".$_GET['rid']."'";	
	}
mysql_select_db($database_hos, $hos);
$rs_drug = "select * from ".$database_kohrx.".kohrx_med_error_report_drug where ".$condition;
$qdrug = mysql_query($rs_drug,$hos)  or die (mysql_error());
$row_drug = mysql_fetch_assoc($qdrug);
$totalRows = mysql_num_rows($qdrug);
}
//===== finish delete ====//

if($_GET['do']=="load"){
if($_GET['rid']==""){
	$condition=" stamp ='".$stamp."'  and (rid='' or rid is NULL)";
	}
else if($_GET['rid']!=""){
	$condition=" rid='".$_GET['rid']."'";
	}
mysql_select_db($database_hos, $hos);
$rs_drug = "select * from ".$database_kohrx.".kohrx_med_error_report_drug where ".$condition;
$qdrug = mysql_query($rs_drug,$hos)  or die (mysql_error());
$row_drug = mysql_fetch_assoc($qdrug);
$totalRows = mysql_num_rows($qdrug);
}




?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="dong.css" rel="stylesheet" type="text/css" />
</head>

<body>
<? if($totalRows<>0){ ?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="table thfont font14">
 <tr>
    <td width="7%" align="center">ลำดับ</td>
    <td width="64%" align="left">ยา</td>
    <td width="18%" align="center">ความเกี่ยวข้อง</td>
    <td width="11%" align="center">&nbsp;</td>
  </tr>
 <? $i=0; do{ $i++; 
 
mysql_select_db($database_hos, $hos);
$rs_drug1 = "select concat(name,strength) as drugname from drugitems where icode='$row_drug[icode]'";
$qdrug1 = mysql_query($rs_drug1,$hos)  or die (mysql_error());
$row_drug1 = mysql_fetch_assoc($qdrug1);

mysql_select_db($database_hos, $hos);
$rs_drug_option = "select drug_option_name from ".$database_kohrx.".kohrx_med_error_drug_option where id='".$row_drug['drug_option']."'";
$qdrug_option = mysql_query($rs_drug_option,$hos)  or die (mysql_error());
$row_drug_option = mysql_fetch_assoc($qdrug_option);
 ?>  <tr>
    <td align="center"><?=$i; ?></td>
    <td align="left"><? echo $row_drug1['drugname']; ?></td>
    <td align="center"><?=$row_drug_option['drug_option_name']; ?></td>
    <td align="center"><a href="javascript:if(confirm('ต้องการลบรายการนี้จริงหรือไม่?')==true){Drugdelete('<? echo $row_drug['id']; ?>');}"><i class="fas fa-eraser font20 text-dark"></i></a></td>
  </tr>
  <? } while($row_drug = mysql_fetch_assoc($qdrug)); 
  mysql_free_result($qdrug1);
  ?>
</table><? } ?>
</body>
</html>
<? 
if(isset($_GET['do'])){
mysql_free_result($qdrug);
}
?>