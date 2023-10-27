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
$query_rs_channel = "select * from ".$database_kohrx.".kohrx_dispensing_setting where name ='television'";
$rs_channel = mysql_query($query_rs_channel, $hos) or die(mysql_error());
$row_rs_channel = mysql_fetch_assoc($rs_channel);
$totalRows_rs_channel = mysql_num_rows($rs_channel);

mysql_select_db($database_hos, $hos);
$query_rs_channel2 = "select * from ".$database_kohrx.".kohrx_dispensing_setting where name ='VDO_display'";
$rs_channel2 = mysql_query($query_rs_channel2, $hos) or die(mysql_error());
$row_rs_channel2 = mysql_fetch_assoc($rs_channel2);
$totalRows_rs_channel2 = mysql_num_rows($rs_channel2);

mysql_select_db($database_hos, $hos);
$query_rs_channel3 = "select * from ".$database_kohrx.".kohrx_dispensing_setting where name ='queue_display'";
$rs_channel3 = mysql_query($query_rs_channel3, $hos) or die(mysql_error());
$row_rs_channel3 = mysql_fetch_assoc($rs_channel3);
$totalRows_rs_channel3 = mysql_num_rows($rs_channel3);

if($row_rs_channel3['value']=="Y"){ $url="queue_caller.php";}
else{
if($row_rs_channel2['value']=="tv"){ $url="tvonline2.php";}
else if($row_rs_channel2['value']=="yt"){ $url="youtube.php";} 		
else if($row_rs_channel2['value']=="mv"){ $url="movieplayer.php";} 		
else if($row_rs_channel2['value']=="sl"){ $url="picture_slideshow.php";} 		

}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</script>
<script type="text/javascript" src="include/fullscreen/jquery.fullscreen.min.js">
</script>
<script language="javascript" type="text/javascript">

$(document).ready(function() {
setInterval(reloadChat, 3000);
$('.close').hide();
});
function reloadChat () {

     $('#result').load('recent_media.php');
}
function page_load(page){
	$("#media_show").attr("src", page);
	}

</script>
</head>

<body style="margin:0px;padding:0px;overflow:hidden" >
<iframe id="media_show" src="<?php echo $url; ?>"style="overflow:hidden;overflow-x:hidden;overflow-y:hidden;height:1080px;width:1980px;position:absolute;top:0px;left:0px;right:0px;bottom:0px" height="100%" width="100%"></iframe>
<div style="position:absolute; top:0px; width:0px; height:0px; background-color:#CCCCCC" id="result"></div>
<div></div>

<div class="open" style="position:absolute; top:20px; left:20px; width:30px; height:0px; cursor:pointer; padding:5px;">
<img src="images/fullscreen.png" width="30" height="30" border="0"  />
</div>
<div class="close" style="position:absolute; top:20px; left:20px; width:30px; height:0px; cursor:pointer; padding:5px;">
<img src="images/exit_fullscreen.png" width="30" height="30" border="0"  />
</div>

<script type="text/javascript">
$(function() {
    $('.open').click(function() {
        $('body').fullscreen();
        return false;
    });
    $('.close').click(function() {
        $.fullscreen.exit();
        return false;
    });
});
</script>
</body>
</html>