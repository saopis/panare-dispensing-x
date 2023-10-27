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
$query_rs_channel = "select * from ".$database_kohrx.".kohrx_recent_media where recent_media ='yt'";
$rs_channel = mysql_query($query_rs_channel, $hos) or die(mysql_error());
$row_rs_channel = mysql_fetch_assoc($rs_channel);
$totalRows_rs_channel = mysql_num_rows($rs_channel);
$youtube=explode('=',$row_rs_channel['value']);

if($row_rs_setting['istatus']!="Y"){
mysql_select_db($database_hos, $hos);
$query_rs_update = "update ".$database_kohrx.".kohrx_recent_media set istatus=NULL where recent_media='tv' or recent_media='sl' or recent_media='qu' or recent_media='mv'";
$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

mysql_select_db($database_hos, $hos);
$query_rs_update = "update ".$database_kohrx.".kohrx_recent_media set istatus='Y' where recent_media='yt'";
$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

}
else{
mysql_select_db($database_hos, $hos);
$query_rs_update = "update ".$database_kohrx.".kohrx_recent_media set istatus=NULL where recent_media='yt'";
$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());
	}
	
mysql_select_db($database_hos, $hos);
$query_rs_update = "update ".$database_kohrx.".kohrx_recent_media2 set istatus=NULL where recent_media='tv' or recent_media='sl' or recent_media='mv'";
$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

mysql_select_db($database_hos, $hos);
$query_rs_update = "update ".$database_kohrx.".kohrx_recent_media2 set channel='".$row_rs_channel['channel']."',istatus='Y' where recent_media='yt'";
$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());
	

?>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="images/favicon2.ico" />
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="include/jquery.js"></script>

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
				href : "include/channel_youtube.php",
				onOpen : function () {
		$('body, html').css('overflowY','hidden');}
				 });

			});
</script>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
</head>

<body >
<div id="player"></div>
<script>
    /* Replace This Code - get Value from playlist page and place it here */
    var playlistId = "<?php echo $row_rs_channel['channel'] ?>";
    var tag = document.createElement('script');
		tag.src = "https://www.youtube.com/iframe_api";

    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    var player;

    function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
            height: '100%',
            width: '100%',
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
<div style="position:absolute; bottom:30px; right:10px; width:40px; height:40px;"><img src="images/socialmedia.png" width="40" height="40" style="cursor:pointer" class="colorbox5" /></div>

</body>
</html>
<?php 
mysql_free_result($rs_setting);
mysql_free_result($rs_channel);
?>