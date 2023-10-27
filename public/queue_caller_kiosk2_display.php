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
	 background: #0072BC;
}
html, body
{
  height: 100%;
  overflow:hidden;
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


.container {
	margin-bottom:115px;
   overflow: hidden;
   
}
    
.right {
    width: 500px;
    float: right;
    background: #FFFFFF;
	height:100vh;
}

.left {
    float: none; /* not needed, just for clarification */
    background: #0072BC;
    /* the next props are meant to keep this block independent from the other floated one */
    width: auto;
    overflow: hidden;
   height: 965px;

}
#slider {
    position: relative;
}
#slider img {
    transition: opacity 1.5s;
    position: absolute;
    top:0;
    left:0;
    opacity:0;
}
#slider img.fadeIn {
    opacity:1;
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
page_load('displayDiv','queue_display7.php?room_id=<?php echo $row_rs_room['id']; ?>')
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

//======= Slide Show ==========//
var curIndex = 0,
    imgDuration = 5000,
    slider = document.getElementById("slider"),
    slides = slider.childNodes; //get a hook on all child elements, this is live so anything we add will get listed
    imgArray = [
        'images/kiosk_slider/doctor1.jpg',
        'images/kiosk_slider/doctor2.jpg',
        'images/kiosk_slider/doctor3.jpg'];


//
// Dynamically add each image frame into the dom;
//
function buildSlideShow(arr) {
    for (i = 0; i < arr.length; i++) {
        var img = document.createElement('img');
        img.src = arr[i];
        slider.appendChild(img);
    }
    // note the slides reference will now contain the images so we can access them
}

//
// Our slideshow function, we can call this and it flips the image instantly, once it is called it will roll
// our images at given interval [imgDuration];
//
function slideShow() {
    
    function fadeIn(e) {
        e.className = "fadeIn";
    };

    function fadeOut(e) {
        e.className = "";
    };

        debugger;

        fadeOut(slides[curIndex]);
        curIndex++;
        if (curIndex === slides.length) {
            curIndex = 0;
        }
        
        fadeIn(slides[curIndex]);

        setTimeout(function () {
            slideShow();
        }, imgDuration);
    };
    buildSlideShow(imgArray);
    slideShow();
//====================================//	
	});

	</script>
<script src="include/jquery.colorbox.js"></script>
<link rel="stylesheet" href="css/colorbox.css" />


 
</head>

<body Onload="startTime()">
<div class="container">

  <div class="right">
        <div style="height:80px; background-color: #1552C1;font-size:60px; color:#FFFFFF; font-weight:bold; padding-top:0px;" align="center" class="thsan-bold">จอแสดงคิวรับบริการ</div>
        <div style="height:80px; background-color: #4EB5FA;font-size:50px; color:#FFFFFF; padding-top:0px;" align="center" class=" thsan-light"><?php echo $row_rs_room['room_name']; ?><iframe src="<?php if($row_channel['caller_tv']=="Y"){ echo "queue_server_requeue.php"; } ?>"  width="0" height="0" frameborder="0"></iframe></div>
        <div style="height:100%;">
        <div align="center" style="background-color:#FFFFFF; padding-top:20px; padding-bottom:10px;">
        <span style="height:80px; font-size:28px; color: #666; font-weight:400; " class="thfont" align="center"><?php echo day_name(date('w'))." วันที่ ".dateThai(date('Y-m-d'))." เวลา "; ?><span id="txt"></span></span>
        </div>
        	<div id="slider" style="height:100%; margin-bottom:0px;"></div>
        </div>
  </div>
        <div class="left">
        	<div id="displayDiv" style="padding:40px;"></div>
        </div>
</div>
<div style="height:115px; width:100%; background-color: #000; bottom:0px; background-color: #01579B; bottom:0px; margin-bottom:0px; position:absolute; margin-bottom:0px;"><iframe src="information_service.php" width="1900"  height="115" frameborder="0" scrolling="no"></iframe></div>
</body>
</html>
<?php
mysql_free_result($channel);

mysql_free_result($rs_room);

?>