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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE error_type SET type_thai=%s, type_eng=%s WHERE id=%s",
                       GetSQLValueString($_POST['thai'], "text"),
                       GetSQLValueString($_POST['eng'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_mederror, $mederror);
  $Result1 = mysql_query($updateSQL, $mederror) or die(mysql_error());

  $updateGoTo = "error_type.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
?>

<?php
mysql_select_db($database_mederror, $mederror);
$query_edit_type = "select * from error_type where id='$id'";
$edit_type = mysql_query($query_edit_type, $mederror) or die(mysql_error());
$row_edit_type = mysql_fetch_assoc($edit_type);
$totalRows_edit_type = mysql_num_rows($edit_type);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
<title>Untitled Document</title>
<link href="../dong.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <table width="400" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="30" align="center" class="normal16"><strong>แก้ไขประเภทความคลาดเคลื่อนทางยา</strong></td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellpadding="5" cellspacing="0" class="normal">
          <tr>
            <td width="17%"><strong>ประเภท</strong></td>
            <td width="18%" class="normal1">ไทย</td>
            <td width="65%"><input name="thai" type="text" id="thai" value="<?php echo $row_edit_type['type_thai']; ?>" size="50" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td class="normal1">อังกฤษ</td>
            <td><input name="eng" type="text" id="eng" value="<?php echo $row_edit_type['type_eng']; ?>" size="50" /></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td align="center"><input type="submit" name="Submit" value="Save" /></td>
    </tr>
  </table>
  <input name="id" type="hidden" id="id" value="<?php echo $row_edit_type['id']; ?>" />
  <input type="hidden" name="MM_update" value="form1">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($edit_type);
?>