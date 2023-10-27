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

$get_ip=$_SERVER["REMOTE_ADDR"];

mysql_select_db($database_hos, $hos);
$query_channel1 = "SELECT channel_name,room_id from kohrx_queue_caller_channel q left outer join kohrx_queue_caller_channel_name n on n.id=q.channel WHERE ip='".$get_ip."'";
$channel1 = mysql_query($query_channel1, $hos) or die(mysql_error());
$row_channel1 = mysql_fetch_assoc($channel1);
$totalRows_channel1 = mysql_num_rows($channel1);

mysql_select_db($database_hos, $hos);
$query_rs_channel = "select * from kohrx_dispensing_setting where name ='television'";
$rs_channel = mysql_query($query_rs_channel, $hos) or die(mysql_error());
$row_rs_channel = mysql_fetch_assoc($rs_channel);
$totalRows_rs_channel = mysql_num_rows($rs_channel);

mysql_select_db($database_hos, $hos);
$query_rs_vdo = "select * from kohrx_dispensing_setting where name ='VDO_display'";
$rs_vdo = mysql_query($query_rs_vdo, $hos) or die(mysql_error());
$row_rs_vdo = mysql_fetch_assoc($rs_vdo);
$totalRows_rs_vdo = mysql_num_rows($rs_vdo);

mysql_select_db($database_hos, $hos);
$query_rs_advertise = "select * from kohrx_queue_caller_advertise where CURDATE() between start_date and end_date";
$rs_advertise = mysql_query($query_rs_advertise, $hos) or die(mysql_error());
$row_rs_advertise = mysql_fetch_assoc($rs_advertise);
$totalRows_rs_advertise = mysql_num_rows($rs_advertise);

mysql_select_db($database_hos, $hos);
$query_rs_config = "select * from opdconfig";
$rs_config = mysql_query($query_rs_config, $hos) or die(mysql_error());
$row_rs_config = mysql_fetch_assoc($rs_config);
$totalRows_rs_config = mysql_num_rows($rs_config);

?>
<?php include('include/function.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
body {
	margin-left: 20px;
	margin-top: 20px;
	margin-right: 20px;
	margin-bottom: 20px;
	background-color: #333;
	overflow:hidden;
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
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="images/favicon2.ico" />
<script  src="include/ajax_framework.js"></script>
<script src="include/jquery.js"></script>
<link type="text/css" href="include/cycle/style.css" rel="stylesheet" />
<script type="text/javascript" src="include/cycle/jquery.cycle.all.js"></script>
<script type="text/javascript" src="include/jquery.marquee.js"></script>
<link type="text/css" href="css/jquery.marquee.css" rel="stylesheet" media="all" />
<script src="http://jwpsrv.com/library/J1hI9n9qEeKVkCIACp8kUw.js"></script>
<script language="JavaScript">
function formSubmit(sID,displayDiv,indicator,eID) {
	if(sID!=""){
	document.getElementById('do').value=sID;		
	var URL = "queue_display.php";		
	}
	if(eID!=""){
	document.getElementById('id').value=eID;
		}		
	var data = getFormData("form1");
	ajaxLoad('post', URL, data, displayDiv,indicator);
	var e = document.getElementById(indicator);
	e.style.display = 'block';
	}

	
	function bodyOnload()
{
	formSubmit('show','displayDiv','indicator')
	setTimeout("doLoop();",5000);
}

function doLoop()
{
	bodyOnload();
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

<script>

$(document).ready(function(){	

				$(".colorbox5").colorbox({
				iframe:true,
				title:'แสดงรายละเอียด',
				width:"60%", 
				height:"60%",
				scrolling:true,
				href : "include/channel_list2.php",
				onOpen : function () {
		$('body, html').css('overflowY','hidden');},
				onCleanup :function(){
    location.reload();}
				 });

			});
			
function requestFullScreen() {

  var el = document.body;

  // Supports most browsers and their versions.
  var requestMethod = el.requestFullScreen || el.webkitRequestFullScreen 
  || el.mozRequestFullScreen || el.msRequestFullScreen;

  if (requestMethod) {

    // Native full screen.
    requestMethod.call(el);

  } else if (typeof window.ActiveXObject !== "undefined") {

    // Older IE.
    var wscript = new ActiveXObject("WScript.Shell");

    if (wscript !== null) {
      wscript.SendKeys("{F11}");
    }
  }
}
$('#exit').click(function() {
    screenfull.exit();
});
</script>
<script type="text/javascript">
$(document).ready(function() {
// Create two variable with the names of the months and days in an array
var monthNames = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ]; 
var dayNames= ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]

// Create a newDate() object
var newDate = new Date();
// Extract the current date from Date object
newDate.setDate(newDate.getDate());
// Output the day, date, month and year    
$('#Date').html(dayNames[newDate.getDay()] + " " + newDate.getDate() + ' ' + monthNames[newDate.getMonth()] + ' ' + newDate.getFullYear());

setInterval( function() {
	// Create a newDate() object and extract the seconds of the current time on the visitor's
	var seconds = new Date().getSeconds();
	// Add a leading zero to seconds value
	$("#sec").html(( seconds < 10 ? "0" : "" ) + seconds);
	},1000);
	
setInterval( function() {
	// Create a newDate() object and extract the minutes of the current time on the visitor's
	var minutes = new Date().getMinutes();
	// Add a leading zero to the minutes value
	$("#min").html(( minutes < 10 ? "0" : "" ) + minutes);
    },1000);
	
setInterval( function() {
	// Create a newDate() object and extract the hours of the current time on the visitor's
	var hours = new Date().getHours();
	// Add a leading zero to the hours value
	$("#hours").html(( hours < 10 ? "0" : "" ) + hours);
    }, 1000);
	
}); 
</script>


</head>

<body Onload="bodyOnload();">
<form id="form1" name="form1" method="post" action="">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="92" colspan="2" align="center" valign="bottom" bgcolor="#292929"  class="gray2" style="font-size:36px; font-weight:bolder; color:#FFFF00; padding:5px; border:solid 0px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="80%" align="right"><span  style="font-size:36px; font-weight:bolder; color:#FFFF00; padding:5px; border:solid 0px"><img src="images/logo.png" width="67" height="67" align="absmiddle" />&nbsp; กลุ่มงานเภสัชกรรมและคุ้มครองผู้บริโภค <?php echo $row_rs_config['hospitalname']; ?>
              <label for="room2"></label>
              <input name="room" type="hidden" id="room" value="<?php echo $row_channel1['room_id']; ?>" />
              <label for="do"></label>
              <input type="hidden" name="do" id="do" />
              <input type="hidden" name="id" id="id" />
          </span></td>
          <td width="20%" valign="middle"><table width="300" border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td align="center"><font style="font-size:25px; color:#FFFFFF; font-weight:400;font-family:'BebasNeueRegular', Arial, Helvetica, sans-serif;"><?php echo dateThai(date('Y-m-d')); ?></font></td>
            </tr>
            <tr>
              <td><div class="clock">
<ul style="margin:0px; alignment-adjust:central; text-align: center">
	<li id="hours"></li>
    <li id="point">:</li>
    <li id="min"> </li>
    <li id="point">:</li>
    <li id="sec"></li>
</ul>
</div></td>
            </tr>
          </table>
          </td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td width="62%" height="406" align="center" valign="top" bgcolor="#292929" ><div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"> <img src="images/indicator.gif" hspace="10" align="absmiddle" width="0px" height="0px" />&nbsp;</div><div id="displayDiv" class="displayIndiv" >&nbsp;</div>
</td>
      <td width="38%" align="center" valign="top" class="gray2"><table width="100%" border="0" cellspacing="0" cellpadding="0" height="860">
       
        <tr>
          <td height="300" bgcolor="#333333"><img src="images/<?php if($totalRows_rs_advertise==0){ echo "process.gif"; } else { echo "advertisement/".$row_rs_advertise['upload_file']; } ?>" width="915" height="300"></td>
        </tr>
        <tr>
          <td height="555" bgcolor="#292929"><iframe width="100%" height="555" src="<?php if($row_rs_vdo['value']=="tv"){ echo "tvonline.php"; } else {echo "youtube.php";} ?>" frameborder="0" scrolling="No"></iframe></td>
        </tr>
      </table></td>
    </tr>
    <tr class="bargreen">
      <td colspan="2" align="center" valign="top" style="padding-top:0px" height="120">
    <iframe src="information_service.php" width="1900"  height="110" frameborder="0" scrolling="no"></iframe>
    </td>
    </tr>
  </table>
</form>
</body>
</html>
<?php

mysql_free_result($rs_channel);

mysql_free_result($rs_advertise);

mysql_free_result($channel1);

mysql_free_result($rs_config);
?>
