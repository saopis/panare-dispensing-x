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
$query_rs_infom = "select * from ".$database_kohrx.".kohrx_information_service where expire_date >CURDATE() order by id DESC";
$rs_infom = mysql_query($query_rs_infom, $hos) or die(mysql_error());
$row_rs_infom = mysql_fetch_assoc($rs_infom);
$totalRows_rs_infom = mysql_num_rows($rs_infom);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="include/jquery/js/jquery.min.js" ></script>
<script type="text/javascript" src="include/marquee/js/jquery.marquee.js"></script>
<link type="text/css" href="include/marquee/css/jquery.marquee.css" rel="stylesheet" media="all" />
<script type="text/javascript">
$(document).ready(function (){
  $("#marquee").marquee({
		scrollSpeed: 10,
		pauseSpeed: 2000
	});
});
</script>
<meta http-equiv='refresh' content='1800' />
</head>

<body>
  <ul id="marquee" class="marquee">
    <?php do { ?>
<li><?php echo $row_rs_infom['information_text']; ?></li>  <?php } while ($row_rs_infom = mysql_fetch_assoc($rs_infom)); ?>
    
  </ul>

</body>
</html>
<?php
mysql_free_result($rs_infom);
?>
