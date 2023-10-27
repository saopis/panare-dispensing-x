<?php require_once('../Connections/mederror.php'); 
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO error_type (type_thai, type_eng, `order_type`) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['thai'], "text"),
                       GetSQLValueString($_POST['eng'], "text"),
                       GetSQLValueString($_POST['order_n'], "int"));

  mysql_select_db($database_mederror, $mederror);
  $Result1 = mysql_query($insertSQL, $mederror) or die(mysql_error());

  $insertGoTo = "error_type.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_mederror, $mederror);
$query_type = "SELECT error_type.`order_type` FROM error_type ORDER BY error_type.`order_type` DESC LIMIT 1";
$type = mysql_query($query_type, $mederror) or die(mysql_error());
$row_type = mysql_fetch_assoc($type);
$totalRows_type = mysql_num_rows($type);
?>
<? $order=$row_type['order_type'];?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
<title>Untitled Document</title>
<link href="../dong.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="400" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="30" align="center" class="normal16"><strong>เพิ่มประเภทความคลาดเคลื่อนทางยา</strong></td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellpadding="5" cellspacing="0" class="normal">
          <tr>
            <td width="17%"><strong>ประเภท</strong></td>
            <td width="18%" class="normal1">ไทย</td>
            <td width="65%"><input name="thai" type="text" id="thai" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td class="normal1">อังกฤษ</td>
            <td><input name="eng" type="text" id="eng" /></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td align="center"><input type="submit" name="Submit" value="Save" /></td>
    </tr>
  </table>
  <input name="order_n" type="hidden" id="order_n" value="<?php echo $order+1; ?>" />
  <input type="hidden" name="MM_insert" value="form1">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($type);
?>
