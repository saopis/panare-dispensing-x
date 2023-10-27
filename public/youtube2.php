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
$youtube=explode('=',$row_rs_channel['value']);

?>
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

	

</script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#slide-img').after('<div id="nav" class="nav">').cycle({
			fx:     'fade',
			speed:  300,
			timeout: 5000
		});
	
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
				width:"100%", 
				height:"100%",
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
<!--
function AutoRefresh(interval) {
	setTimeout("location.reload(true);",interval);
}
//   -->
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

<body onLoad="JavaScript: AutoRefresh(1200000);">
<div id="player"></div>
<script>
    /* Replace This Code - get Value from playlist page and place it here */
    var playlistId = "<?php echo $youtube[1]; ?>";
    var tag = document.createElement('script');
		tag.src = "https://www.youtube.com/iframe_api";

    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    var player;

    function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
            height: '540',
            width: '915',
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            }

        });
    }


    var playlistArray;
    var playListArrayLength;
    var maxNumber;

    var oldNumber = 0;
    var NewNumber = 0;

    function newRandomNumber() {
        oldNumber = NewNumber;
        NewNumber = Math.floor(Math.random() * maxNumber);
        if (NewNumber == oldNumber) {
            newRandomNumber();
        } else {
            return NewNumber;
        }
    }

function onPlayerReady(event) {
    player.loadPlaylist({
        'listType': 'playlist',
        'list': playlistId
    });
}

var firstLoad = true;
function onPlayerStateChange(event) {
    console.log(event.data);
    if (event.data == YT.PlayerState.ENDED) {
        player.playVideoAt(newRandomNumber());   
    } else {
        if (firstLoad && event.data == YT.PlayerState.PLAYING) {
            firstLoad = false;
            playlistArray = player.getPlaylist();
            playListArrayLength = playlistArray.length;
            maxNumber = playListArrayLength;
            NewNumber = newRandomNumber();
            player.playVideoAt(newRandomNumber());
        }
    }
}    
    
</script>
</body>
</html>