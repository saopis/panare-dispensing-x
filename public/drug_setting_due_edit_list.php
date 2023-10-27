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
if(isset($_GET['action'])&&$_GET['action']=="delete"){
	mysql_select_db($database_hos, $hos);
	$query_delete = "delete from ".$database_kohrx.".kohrx_due_cause where id='".$_GET['id']."'";
	$rs_delete = mysql_query($query_delete, $hos) or die(mysql_error());
	
//insert replicate_log		
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_due_cause where id=\'".$_GET['id']."\'')
";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

	}

if(isset($_GET['action'])&&$_GET['action']=="edit"){
	mysql_select_db($database_hos, $hos);
	$query_update = "update ".$database_kohrx.".kohrx_due_cause set use_cause='".$_GET['cause']."' where id='".$_GET['id']."'";
	$rs_update = mysql_query($query_update, $hos) or die(mysql_error());

//insert replicate_log		
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_due_cause set use_cause=\'".$_GET['cause']."\' where id=\'".$_GET['id']."\'')
";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

	}

if(isset($_POST['delete'])&&$_POST['delete']=="ลบ"){
	mysql_select_db($database_hos, $hos);
	$query_delete = "delete from ".$database_kohrx.".kohrx_due_cause where icode='$icode'";
	$rs_delete = mysql_query($query_delete, $hos) or die(mysql_error());

//insert replicate_log		
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_due_cause where icode=\'".$icode."\'')
";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

	mysql_select_db($database_hos, $hos);
	$query_delete = "delete from ".$database_kohrx.".kohrx_due_drug where icode='$icode'";
	$rs_delete = mysql_query($query_delete, $hos) or die(mysql_error());

//insert replicate_log		
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_due_drug where icode=\'".$icode."\'')
";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

echo "<script>parent.$.fn.colorbox.close();</script>";
exit();

}
	
if(isset($_GET['action'])&&$_GET['action']=="save"){
	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into ".$database_kohrx.".kohrx_due_cause (icode,use_cause) values('".$_GET['icode']."','".$_GET['cause']."')";
	$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());

//insert replicate_log		
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_due_cause (icode,use_cause) values(\'".$_GET['icode']."\',\'".$_GET['cause']."\')')
";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

	}
	

mysql_select_db($database_hos, $hos);
$query_rs_drug = "select c.id,concat(d.name,d.strength) as drugname,use_cause,k.icode from ".$database_kohrx.".kohrx_due_drug k left outer join drugitems d on d.icode=k.icode left outer join ".$database_kohrx.".kohrx_due_cause c on c.icode=k.icode where k.icode='".$_GET['icode']."' and use_cause !='' ";
//echo $query_rs_drug;
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);

mysql_select_db($database_hos, $hos);
$query_rs_edit = "select * from ".$database_kohrx.".kohrx_due_cause where id='$id'";
$rs_edit = mysql_query($query_rs_edit, $hos) or die(mysql_error());
$row_rs_edit = mysql_fetch_assoc($rs_edit);
$totalRows_rs_edit = mysql_num_rows($rs_edit);
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<?php if ($totalRows_rs_drug > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" cellpadding="3" cellspacing="0"  id="table" class="table table-striped table-bordered table-sm table-hover " style="width:100%; ">    <?php $i=0; do {$i++; ?>
    <tr>
      <td class="text-center"><?php echo $i; ?></td>
      <td ><?php echo $row_rs_drug['use_cause']; ?></td>
      <td class="text-center"><nobr><i class="fas fa-edit font20" style="cursor: pointer" onClick="due_edit('<?php echo $row_rs_drug['id']; ?>','<?php echo $row_rs_drug['use_cause']; ?>');"></i>&ensp;<i class="fas fa-trash-alt font20" style="cursor: pointer" onClick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){due_delete('<?php echo $row_rs_drug['id']; ?>','<?php echo $_GET['icode']; ?>');}"></i></nobr></td>
    </tr>      <?php } while ($row_rs_drug = mysql_fetch_assoc($rs_drug)); ?>
    
  </table>
  <br />
<?php } // Show if recordset not empty ?>

</body>
</html>
<?php
mysql_free_result($rs_drug);
	
mysql_free_result($rs_edit);

?>
