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
?>
<? include("include/FCKeditor/fckeditor.php") ; ?>
<?php include("include/function.php"); ?>
<?php

	if(isset($_GET['hn'])){
	$an=$_GET['hn']; 
	}
	if(isset($_POST['hn'])){
	$an=$_POST['hn']; 
	}
if(isset($_POST['del'])&&($_POST['del']=="ลบรายการ")){
mysql_select_db($database_hos, $hos);
$query_delete = "delete from ".$database_kohrx.".kohrx_pharmacist_note where id='".$_POST['id']."'";
$rs_delete = mysql_query($query_delete, $hos) or die(mysql_error());

//insert rx_operator_id ใน replicate_log
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_pharmacist_note where id=\'".$_POST['id']."\'')
";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

}
if(isset($_POST['save'])&&($_POST['save']=="แก้ไข")){
mysql_select_db($database_hos, $hos);
$query_update = "update ".$database_kohrx.".kohrx_pharmacist_note set  pharmacist_note='".$_POST['text']."',pharmacist='".$_POST['doctor']."' where id='".$_POST['id']."'";
$rs_update = mysql_query($query_update, $hos) or die(mysql_error());

//insert rx_operator_id ใน replicate_log
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_pharmacist_note set  pharmacist_note=\'".$_POST['text']."\',pharmacist=\'".$_POST['doctor']."\' where id=\'".$_POST['id']."\'')
";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

}
if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){
mysql_select_db($database_hos, $hos);
$query_insert = "insert into ".$database_kohrx.".kohrx_pharmacist_note (patient_type,hn,note_date,note_time,pharmacist_note,pharmacist) value ('IPD','".$_POST['hn']."',NOW(),NOW(),'".$_POST['text']."','".$_POST['doctor']."')";
$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());

//insert rx_operator_id ใน replicate_log
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_pharmacist_note (patient_type,hn,note_date,note_time,pharmacist_note,pharmacist) value (\'IPD\',\'".$_POST['hn']."\',NOW(),NOW(),\'".$_POST['text']."\',\'".$_POST['doctor']."\')')
";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

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
$query_person_error = "SELECT o.name,o.doctorcode,o.hospital_department_id FROM opduser o left outer join doctor d on d.code=o.doctorcode WHERE o.hospital_department_id ='".$row_setting[1]."' and d.active='Y' ORDER BY name";
$person_error = mysql_query($query_person_error, $hos) or die(mysql_error());
$row_person_error = mysql_fetch_assoc($person_error);
$totalRows_person_error = mysql_num_rows($person_error);

mysql_select_db($database_hos, $hos);
$query_rs_note = "select n.*,d.name from ".$database_kohrx.".kohrx_pharmacist_note n left outer join doctor d on d.code=n.pharmacist where hn='".$hn."' and patient_type='IPD' order by note_date,note_time DESC";
$rs_note = mysql_query($query_rs_note, $hos) or die(mysql_error());
$row_rs_note = mysql_fetch_assoc($rs_note);
$totalRows_rs_note = mysql_num_rows($rs_note);

if(isset($_GET['id'])&&($_GET['id']!="")){
mysql_select_db($database_hos, $hos);
$query_rs_edit = "select * from ".$database_kohrx.".kohrx_pharmacist_note where id='".$_GET['id']."'";
$rs_edit = mysql_query($query_rs_edit, $hos) or die(mysql_error());
$row_rs_edit = mysql_fetch_assoc($rs_edit);
$totalRows_rs_edit = mysql_num_rows($rs_edit);
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php include('java_function.php'); ?>
<link rel="stylesheet" href="css/jquery-mobile/jquery.mobile-1.4.5.min.css" />
<script type="text/javascript" src="include/nicEdit1.js"></script>
<script type="text/javascript">
//	bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
</script>
 <link rel="stylesheet" href="css/transition/animsition.css">
 		<link type="text/css" rel="stylesheet" href="css/sidebar.css" />
		<link type="text/css" rel="stylesheet" href="css/jquery.mmenu.all.css" />
        
	<script type="text/javascript" src="include/htmlbox/jquery.codify.min.js"></script>
	<script type="text/javascript" src="include/htmlbox/htmlbox.colors.js"></script>
	<script type="text/javascript" src="include/htmlbox/htmlbox.styles.js"></script>
	<script type="text/javascript" src="include/htmlbox/htmlbox.syntax.js"></script>
	<script type="text/javascript" src="include/htmlbox/htmlbox.undoredomanager.js"></script>
	<script type="text/javascript" src="include/htmlbox/htmlbox.min.js"></script>
<script>
function resutName(icode,doctor)
	{
		if(icode!=""){
		switch(icode)
		{
			<?
			mysql_select_db($database_hos, $hos);

			$strSQL = "SELECT o.name,o.doctorcode FROM opduser o left outer join doctor d on d.code=o.doctorcode where d.active='Y' and o.hospital_department_id='".$row_setting[1]."'";
			$objQuery = mysql_query($strSQL);
			while($objResult = mysql_fetch_array($objQuery))
			{
			?>
				case "<?=$objResult["doctorcode"];?>":
				document.getElementById(doctor).value = "<?=$objResult["doctorcode"];?>";
							
				break;
			<?
			}
			?>
			default:
			 document.getElementById(doctor).value = "<?php echo $_SESSION['doctorcode']; ?>";
		}
		}
	}
	
function doctorcode(icode,doctor){
	document.getElementById(doctor).value=icode;
	}

</script>
<style>
table.table_bord1 tr td{border:solid 1px #CCCCCC; background-color:#FFF}
table.table_bord1{border-collapse:collapse; border-left:0px;}
table.table_bord1 tr.head td{background-color: #CED7E3;}
</style>
</head>

<body>
<div style="padding:20px"><div class="thfont font14" style="margin-bottom:10px">บันทึกการดูแลผู้ป่วยในสำหรับเภสัชกร</div>
<form name="form1" method="post">
  <textarea name="text" id="htmlbox_icon_set_default" style="height:100%"><?php echo $row_rs_edit['pharmacist_note']; ?></textarea><script language="JavaScript" type="text/javascript">
var hb_icon_set_default = $("#htmlbox_icon_set_default").css("height","100").css("width","600").htmlbox({
    toolbars:[
	     ["cut","copy","paste","separator_dots","bold","italic","underline","strike","separator","sub","sup","separator_dots","undo","redo","separator_dots",
		 "left","center","right","justify","separator_dots","ol","ul","indent","outdent","separator_dots","link","unlink","image"],
		 ["code","removeformat","striptags","separator_dots","quote","paragraph","hr","separator_dots"		  ]
	],
	icons:"default",
	skin:"default"
});
</script>
  <input name="hn" type="hidden" id="hn" value="<?php echo $_GET['hn']; ?>">
  <input name="id" type="hidden" id="id" value="<?php echo $row_rs_edit['id']; ?>">
  <br>
  ผู้บันทึก <span class="table_head_small">
  <input name="person_code" type="text" class="inputcss1" id="person_code"  onkeyup="resutName(this.value,'doctor')"  size="2" onKeyPress="return isNumberKey(event);" value="<?php if($totalRows_rs_edit<>0){ echo $row_rs_edit['pharmacist'];} else { echo $_SESSION['doctorcode']; } ?>"   />
  </span>
  <select name="doctor" id="doctor" class="inputcss1 thsan-light" onChange="doctorcode(this.value,'person_code')">
    <?php
do {  
	if($totalRows_rs_edit<>0){
		$pharmacist=$row_rs_edit['pharmacist'];
		}
	else{
		$pharmacist=$_SESSION['doctorcode'];
		}
?>
    <option value="<?php echo $row_person_error['doctorcode']?>"<?php if (!(strcmp($row_person_error['doctorcode'], $pharmacist))) {echo "selected=\"selected\"";} ?>><?php echo $row_person_error['name']?></option>
    <?php
} while ($row_person_error = mysql_fetch_assoc($person_error));
  $rows = mysql_num_rows($person_error);
  if($rows > 0) {
      mysql_data_seek($person_error, 0);
	  $row_person_error = mysql_fetch_assoc($person_error);
  }
?>
  </select>
  <span style="margin-top:10px">
  <input type="submit" name="save" id="save" value="<?php if(isset($_GET['id'])&&($_GET['id']!="")){ echo "แก้ไข"; } else { echo "บันทึก"; } ?>" class=" button  thsan-semibold" style="font-size:12px; border:0px">
 <?php if(isset($_GET['id'])&&($_GET['id']!="")){?> <input name="del" id="del" type="submit" value="ลบรายการ" onClick="return confirm('ต้องการลบรายการนี้จริงหรือไม่');" class=" button thsan-semibold" style="background-color:#F00; color:#FFFFFF; font-size:12px; border:0px"><?php } ?>
  </span>
<div style="margin-top:10px">&nbsp;</div>
</form>
</div>
<div style="padding:20px; padding-top:0px">
  <?php if ($totalRows_rs_note > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" cellspacing="0" cellpadding="3" class="thsan-light font15 table_bord1">
    <tr class="head">
      <td width="15%" align="center">วันที่</td>
      <td width="8%" align="center">เวลา</td>
      <td width="55%" align="center">บันทึก</td>
      <td width="20%" align="center">ผู้บนทึก</td>
      <td width="2%" align="center">&nbsp;</td>
    </tr>
    <?php do { ?>
      <tr class="grid4">
        <td align="center" valign="top"><?php echo dateThai($row_rs_note['note_date']); ?></td>
        <td align="center" valign="top"><?php echo $row_rs_note['note_time']; ?></td>
        <td valign="top"><?php echo $row_rs_note['pharmacist_note']; ?></td>
        <td align="center" valign="top"><?php echo $row_rs_note['name']; ?></td>
        <td valign="top" style="cursor:pointer" onClick="window.location='pharmacist_note_ipd.php?hn=<?php echo $hn; ?>&id=<?php echo $row_rs_note['id']; ?>'"><img src="images/gtk-edit.png" width="16" height="16"></td>
      </tr>
      <?php } while ($row_rs_note = mysql_fetch_assoc($rs_note)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
</div>
</body>
</html>
<?php

mysql_free_result($rs_note);

mysql_free_result($rs_setting);

if(isset($_GET['id'])&&($_GET['id']!="")){
mysql_free_result($rs_edit);
}
?>
