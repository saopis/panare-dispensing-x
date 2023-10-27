<?php require_once('../Connections/mederror.php'); ?>
<?php
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
  $updateSQL = sprintf("UPDATE config SET hcode=%s WHERE id=%s",
                       GetSQLValueString($_POST['hcode'], "int"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_mederror, $mederror);
  $Result1 = mysql_query($updateSQL, $mederror) or die(mysql_error());

  $updateGoTo = "config.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_mederror, $mederror);
$query_name = "SELECT concat( hospcode.hosptype,hospcode.name) FROM hospcode WHERE hospcode.hospcode = (select hcode from config where id = 1)";
$name = mysql_query($query_name, $mederror) or die(mysql_error());
$row_name = mysql_fetch_assoc($name);
$totalRows_name = mysql_num_rows($name);

mysql_select_db($database_mederror, $mederror);
$query_config_h = "SELECT * FROM config WHERE config.id=1";
$config_h = mysql_query($query_config_h, $mederror) or die(mysql_error());
$row_config_h = mysql_fetch_assoc($config_h);
$totalRows_config_h = mysql_num_rows($config_h);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">



<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
<title>Untitled Document</title>
<link href="../dong.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
}
-->
</style></head>

<body>
<table width="100%" border="0" align="left" cellpadding="5" cellspacing="0">
  <tr>
    <td align="center"><span class="big_blue">MEDICATION ERROR CONFIG </span><br />
      <span class="normal">( ปรับแต่ค่าเริ่มต้น )</span></td>
  </tr>
  <tr>
    <td><form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="500" border="0" align="center" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" style="border:solid 1px #000000">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
              <tr>
                <td bgcolor="#6699CC" class="white_middle">สถานพยาบาล</td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellpadding="3" cellspacing="0" class="normal1">
                  <tr>
                    <td width="25%">รหัสสถานพยาบาล : </td>
                    <td width="75%"><input name="hcode" type="text" id="hcode" value="<?php echo $row_config_h['hcode']; ?>" size="5" /></td>
                  </tr>
                  <tr>
                    <td><input name="id" type="hidden" id="id" value="1" /></td>
                    <td><?php echo $row_name['concat( hospcode.hosptype,hospcode.name)']; ?></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td><input type="submit" name="Submit" value="Save" /></td>
              </tr>
              
          </table></td>
        </tr>
      </table>
        <input type="hidden" name="MM_update" value="form1">
    </form>
    </td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($name);

mysql_free_result($config_h);
?>
