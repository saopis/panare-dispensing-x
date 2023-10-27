<?
ob_start();
session_start();

date_default_timezone_set("Asia/Bangkok");

?>
<?php require_once('Connections/hos.php'); ?>
<?php require_once('Connections/hos2.php'); ?>
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

$get_ip=$_SERVER["REMOTE_ADDR"];

mysql_select_db($database_hos, $hos);
$query_channel = "SELECT channel_name,cursor_position,caller_tv,queue_display from ".$database_kohrx.".kohrx_queue_caller_channel q left outer join ".$database_kohrx.".kohrx_queue_caller_channel_name n on n.id=q.channel WHERE ip='".$get_ip."'";
$channel = mysql_query($query_channel, $hos) or die(mysql_error());
$row_channel = mysql_fetch_assoc($channel);
$totalRows_channel = mysql_num_rows($channel);

mysql_select_db($database_hos, $hos);
$query_rs_room = "select room_name,r.id from ".$database_kohrx.".kohrx_queue_caller_channel c left outer join ".$database_kohrx.".kohrx_queue_caller_room r on r.id=c.room_id where ip='".$get_ip."'";
$rs_room = mysql_query($query_rs_room, $hos) or die(mysql_error());
$row_rs_room = mysql_fetch_assoc($rs_room);
$totalRows_rs_room = mysql_num_rows($rs_room);

?>
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
html, body
{
  height: 100%;
}

a { 
	text-decoration:none; 
	color:#00c6ff;
}


@font-face {
    font-family: 'BebasNeueRegular';
    src: url('BebasNeue-webfont.eot');
    src: url('BebasNeue-webfont.eot?#iefix') format('embedded-opentype'),
         url('BebasNeue-webfont.woff') format('woff'),
         url('BebasNeue-webfont.ttf') format('truetype'),
         url('BebasNeue-webfont.svg#BebasNeueRegular') format('svg');
    font-weight: normal;
    font-style: normal;

}

.clock {width:300px;   border:0px solid #333; color:#fff; alignment-adjust:central; }

ul { width:300px; padding:0px ; }
ul li { display:inline; font-size:50px; text-align:center; font-family:'BebasNeueRegular', Arial, Helvetica, sans-serif;}

#point { position:relative; -moz-animation:mymove 1s ease infinite; -webkit-animation:mymove 1s ease infinite; padding-left:0px; padding-right:0px; }

@-webkit-keyframes mymove 
{
0% {opacity:1.0; text-shadow:0 0 5px #00c6ff;}
50% {opacity:0; text-shadow:none; }
100% {opacity:1.0; text-shadow:0 0 5px #00c6ff; }	
}


@-moz-keyframes mymove 
{
0% {opacity:1.0; text-shadow:0 0 5px #00c6ff;}
50% {opacity:0; text-shadow:none; }
100% {opacity:1.0; text-shadow:0 0 5px #00c6ff; }	
}
</style>
<style>
* {
    box-sizing: border-box;
}

.column {
    float: left;
    padding: 0px;
    height: 965px; /* Should be removed. Only for demonstration */
}

.left {
  width: 70%;
}

.right {
  width: 30%;
}
/* Clear floats after the columns */
.row:after {
    content: "";
    display: table;
    clear: both;
}

</style>
<?php include('include/function.php'); ?>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="images/favicon2.ico" />
<script src="include/jquery.js"></script>
<link type="text/css" href="include/cycle/style.css" rel="stylesheet" />
<script type="text/javascript" src="include/cycle/jquery.cycle.all.js"></script>
<script type="text/javascript" src="include/jquery.marquee.js"></script>
<link type="text/css" href="css/jquery.marquee.css" rel="stylesheet" media="all" />
<script src="http://jwpsrv.com/library/J1hI9n9qEeKVkCIACp8kUw.js"></script>
<script language="JavaScript">
function page_load(divid,page){
	$('#indicator').show();
	$("#"+divid).load(page,function(responseTxt, statusTxt, xhr){
        if(statusTxt == "success")
		$('#indicator').hide();            
    });
	}
	
$(document).ready(function(){	
setInterval(function(){
page_load('displayDiv','<?php if($row_channel['queue_display']==1){ echo "queue_display5"; } else {echo "queue_display_next4"; } ?>.php?room_id=<?php echo $row_rs_room['id']; ?>')
}, 3000);
setInterval(function(){
page_load('q_next','<?php if($row_channel['queue_display']==1){ echo "queue_display_next3"; } else {echo "queue_display6"; } ?>.php?room_id=<?php echo $row_rs_room['id']; ?>')
}, 3000);


			});
			
function startTime() {
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById('txt').innerHTML =
    h + ":" + m + ":" + s;
    var t = setTimeout(startTime, 500);
}
function checkTime(i) {
    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}
</script>
<script type="text/javascript">
	$(document).ready(function() {
	
  $("#marquee").marquee({
		scrollSpeed: 20,
		pauseSpeed: 2000
	});
	
	});

	</script>
<script src="include/jquery.colorbox.js"></script>
<link rel="stylesheet" href="css/colorbox.css" />


 
</head>

<body Onload="startTime()">
  <div style="width:100%; height:1080px;" >
  <div class="row">
	<div class=" column left" style="background-color:#ccc;">
    	<div style="height:80px; background-color:#59B4E3; color:#0052A4; "  align="left"><span style="margin-left:20px; font-size:30px;" class="thfont font_bord">ระบบเรียกคิว&nbsp;:</span>&nbsp;<span style="font-weight:bolder; color: #039; font-size:50px;"><?php echo $row_rs_room['room_name']; ?></span>&nbsp;&nbsp;<span style="height:80px; font-size:25px; color:#FFFFFF; font-weight:400;font-family:'BebasNeueRegular', Arial, Helvetica, sans-serif; padding-top:10px;" align="center"><?php echo day_name(date('w'))." วันที่ ".dateThai(date('Y-m-d'))." เวลา "; ?><span id="txt"></span></span><iframe src="<?php if($row_channel['caller_tv']=="Y"){ echo "queue_server_requeue.php"; } ?>" width="0" height="0"></iframe></div>
    	<div id="displayDiv" class="displayIndiv"  >&nbsp;</div>
    </div>
    <div class=" column right"  style="background-color:#bbb;">
        <div style="height:80px; background-color: #317DA6;font-size:50px; color:#FFFFFF; font-weight:400;font-family:'BebasNeueRegular', Arial, Helvetica, sans-serif; padding-top:10px;" align="center">คิวที่<?php if($row_channel['queue_display']==1){ echo "รอเรียก"; } else { echo "เรียกแล้ว";} ?></div>
       <div id="q_next"></div>
    </div>
</div>
<div style="height:115px; width:100%; background-color: #000; bottom:0px; background-color: #01579B"><iframe src="information_service.php" width="1900"  height="115" frameborder="0" scrolling="no"></iframe></div>
</div>
</body>
</html>
<?php
mysql_free_result($channel);

mysql_free_result($rs_room);

?>