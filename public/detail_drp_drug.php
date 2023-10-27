<?php 
require_once('Connections/hos.php');
?>
<?
if($_GET['do']=="insert"){
if($_GET['id']!=""){
mysql_select_db($database_hos, $hos);
$insert = "insert into ".$database_kohrx.".kohrx_drp_drug (icode,drp_id) value ('".$_GET['icode']."','".$_GET['id']."')";
$qinsert = mysql_query($insert,$hos)  or die (mysql_error());
	}
else{
mysql_select_db($database_hos, $hos);
$insert = "insert into ".$database_kohrx.".kohrx_drp_drug (icode) value ('".$_GET['icode']."')";
$qinsert = mysql_query($insert,$hos)  or die (mysql_error());
}
}

if($_POST['do']=="update"){
$drugcode=explode('/',$_POST['drug']);
mysql_select_db($database_hos, $hos);
$insert = "update med_error_report_drug set icode='$drugcode[0]',did='$drugcode[1]',drug_option='$_POST[drug_option]',stamp='$_POST[stamp]' where id='$id2'";
$qinsert = mysql_query($insert,$hos)  or die (mysql_error());
}

if($_GET['do']=="delete"){
mysql_select_db($database_hos, $hos);
$insert = "delete from ".$database_kohrx.".kohrx_drp_drug where id='".$_GET['icode']."'";
$qinsert = mysql_query($insert,$hos)  or die (mysql_error());
}
if($_GET['id']!=""){
	$condition="drp_id='".$_GET['id']."'";
}
if($_GET['id']==""){
	$condition=" drp_id is NULL";
	}
mysql_select_db($database_hos, $hos);
$rs_drug = "select * from ".$database_kohrx.".kohrx_drp_drug where ".$condition;
$qdrug = mysql_query($rs_drug,$hos)  or die (mysql_error());
$row_drug = mysql_fetch_assoc($qdrug);
$totalRows = mysql_num_rows($qdrug);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="mederror/dong.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php if($totalRows<>0){ ?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="normal">
 <? $i=0; do{ $i++; 
 
mysql_select_db($database_hos, $hos);
$rs_drug1 = "select concat(name,strength) as drugname from drugitems where icode='$row_drug[icode]'";
$qdrug1 = mysql_query($rs_drug1,$hos)  or die (mysql_error());
$row_drug1 = mysql_fetch_assoc($qdrug1);
 ?>  <tr>
    <td width="10%" align="center"><?=$i; ?></td>
    <td width="90%" align="left"><? echo $row_drug1['drugname']; ?>&ensp;<a href="javascript:if(confirm('ต้องการลบรายการนี้จริงหรือไม่?')==true){page_load2('Drugadd','detail_drp_drug.php','delete','<?php echo $_GET['id']; ?>','<? echo $row_drug['id']; ?>');}"><i class="fas fa-eraser font20"></i></a></td>
    </tr>
  <? } while($row_drug = mysql_fetch_assoc($qdrug)); 
  mysql_free_result($qdrug1);
  ?>
</table>
<?php } ?>
</body>
</html>
<? 
mysql_free_result($qdrug);
?>