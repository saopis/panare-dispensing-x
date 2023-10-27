<?
ob_start();
session_start();
?>
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

mysql_select_db($database_hos, $hos);
$query_rs_recent = "select * from ".$database_kohrx.".kohrx_recent_payment where doctorcode='".$_SESSION['doctorcode']."'";
$rs_recent = mysql_query($query_rs_recent, $hos) or die(mysql_error());
$row_rs_recent = mysql_fetch_assoc($rs_recent);
$totalRows_rs_recent = mysql_num_rows($rs_recent);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php include('java_css_file.php'); ?>
<script>
<?php if($totalRows_rs_recent<>0){ ?>
$(document).ready(function(e) {
    <?php if(($row_rs_recent['print_staff']!=""||$row_rs_recent['print_staff']!=NULL)&&($_GET['action']=="payment")){ ?>
	$('#rx_print').val('<?php echo $row_rs_recent['print_staff']; ?>');
    $('#doctorprint').val('<?php echo $row_rs_recent['print_staff']; ?>');
	<?php } ?>
    <?php if(($row_rs_recent['prepare_staff']!=""||$row_rs_recent['prepare_staff']!=NULL)&&($_GET['action']=="payment")){ ?>
	$('#prepare').val('<?php echo $row_rs_recent['prepare_staff']; ?>');
    $('#preparedoctor').val('<?php echo $row_rs_recent['prepare_staff']; ?>');
	<?php } ?>
    <?php if(($row_rs_recent['check_staff']!=""||$row_rs_recent['check_staff']!=NULL)&&($_GET['action']=="payment")){ ?>
	$('#check').val('<?php echo $row_rs_recent['check_staff']; ?>');
    $('#checkdoctor').val('<?php echo $row_rs_recent['check_staff']; ?>');
	<?php } ?>
    <?php if(($row_rs_recent['pay_staff']!=""||$row_rs_recent['pay_staff']!=NULL)&&($_GET['action']=="payment")){ ?>
	$('#dispen').val('<?php echo $row_rs_recent['pay_staff']; ?>');
    $('#dispendoctor').val('<?php echo $row_rs_recent['pay_staff']; ?>');
	<?php } ?>
	<?php if(($row_rs_recent['respondent']!=""||$row_rs_recent['respondent']!=NULL)&&($_GET['action']=="adr")){ ?>
	$('#respondent').val('<?php echo $row_rs_recent['respondent']; ?>');
    $('#respondent_list').val('<?php echo $row_rs_recent['respondent']; ?>');
	$('#respondent2').val('<?php echo $row_rs_recent['respondent']; ?>');
    $('#respondent_list2').val('<?php echo $row_rs_recent['respondent']; ?>');
	<?php } ?>
	<?php if(($row_rs_recent['answer']!=""||$row_rs_recent['answer']!=NULL)&&($_GET['action']=="adr")){ ?>
	$('#answer').val('<?php echo $row_rs_recent['answer']; ?>');
    $('#answer_list').val('<?php echo $row_rs_recent['answer']; ?>');
	<?php } ?>

});
<?php } ?>
</script>
</head>

<body>
</body>
</html>
<?php
mysql_free_result($rs_recent);
?>
