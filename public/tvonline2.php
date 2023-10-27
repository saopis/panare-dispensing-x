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
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_recent_media where recent_media = 'qu'";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

mysql_select_db($database_hos, $hos);
$query_rs_channel = "select * from ".$database_kohrx.".kohrx_recent_media where recent_media ='tv'";
$rs_channel = mysql_query($query_rs_channel, $hos) or die(mysql_error());
$row_rs_channel = mysql_fetch_assoc($rs_channel);
$totalRows_rs_channel = mysql_num_rows($rs_channel);

if($row_rs_setting['istatus']!="Y"){
mysql_select_db($database_hos, $hos);
$query_rs_update = "update ".$database_kohrx.".kohrx_recent_media set istatus=NULL where recent_media='yt' or recent_media='sl' or recent_media='qu' or recent_media='mv'";
$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

mysql_select_db($database_hos, $hos);
$query_rs_update = "update ".$database_kohrx.".kohrx_recent_media set istatus='Y' where recent_media='tv'";
$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());
}
else{
mysql_select_db($database_hos, $hos);
$query_rs_update = "update ".$database_kohrx.".kohrx_recent_media set istatus=NULL where recent_media='tv'";
$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());
	
	}
mysql_select_db($database_hos, $hos);
$query_rs_update = "update ".$database_kohrx.".kohrx_recent_media2 set istatus=NULL where recent_media='yt' or recent_media='sl' or recent_media='mv'";
$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

mysql_select_db($database_hos, $hos);
$query_rs_update = "update ".$database_kohrx.".kohrx_recent_media2 set channel='".$row_rs_channel['channel']."',istatus='Y' where recent_media='tv'";
$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

?>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="images/favicon2.ico" />
<script src="include/jquery.js"></script>
<link rel="stylesheet" href="https://releases.flowplayer.org/7.2.6/skin/skin.css">
  <script src="https://releases.flowplayer.org/7.2.6/flowplayer.min.js"></script>

<script src="include/jquery.colorbox.js"></script>
<link rel="stylesheet" href="css/colorbox.css" />

<script>

$(document).ready(function(){	

				$(".colorbox5").colorbox({
				iframe:true,
				title:'แสดงรายละเอียด',
				width:"90%", 
				height:"90%",
				scrolling:true,
				href : "include/channel_list1.php",
				onOpen : function () {
		$('body, html').css('overflowY','hidden');}
				 });

			});
			
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
</head>

<body>
<div class="flowplayer " data-autoplay="true"    >
    <video>
		<source type="application/x-mpegurl"
        src="<?php echo $row_rs_channel['channel']; ?>">
    </video>
</div>
<div style="position:absolute; bottom:10px; right:10px; width:40px; height:40px;"><img src="images/socialmedia.png" width="40" height="40" style="cursor:pointer" class="colorbox5" /></div>
</html>
<?php
mysql_free_result($rs_channel);
mysql_free_result($rs_setting);

?>