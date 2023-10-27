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

switch($_GET['menu_type']){
	case "":
	$table="main_menu";
	break;
	case "main":
	$table="sub_menu";
	break;
	case "sub_menu":
	$table="sub_menu2";
	break;
	case "sub_menu2":
	$table="sub_menu3";
	break;
}

mysql_select_db($database_hos, $hos);
$query_rs_text = "select * from ".$database_kohrx.".kohrx_".$table." where id='$menu_id' ";
$rs_text = mysql_query($query_rs_text, $hos) or die(mysql_error());
$row_rs_text = mysql_fetch_assoc($rs_text);
$totalRows_rs_text = mysql_num_rows($rs_text);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php echo $row_rs_text['menu_text']; ?>
</body>
</html>
<?php
mysql_free_result($rs_text);
?>
