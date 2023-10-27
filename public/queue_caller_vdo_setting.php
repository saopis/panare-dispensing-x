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
if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){

mysql_select_db($database_hos, $hos);
$query_rs_channel = "select * from ".$database_kohrx.".kohrx_recent_media where recent_media ='".$_POST['radio']."'";
$rs_channel = mysql_query($query_rs_channel, $hos) or die(mysql_error());
$row_rs_channel = mysql_fetch_assoc($rs_channel);
$totalRows_rs_channel = mysql_num_rows($rs_channel);

mysql_select_db($database_hos, $hos);
$query_update_setting = "update ".$database_kohrx.".kohrx_dispensing_setting set value='".$_POST['radio']."'  where id=23";
$rs_update = mysql_query($query_update_setting, $hos) or die(mysql_error());

mysql_select_db($database_hos, $hos);
$query_update_setting = "update ".$database_kohrx.".kohrx_dispensing_setting set  value='".$row_rs_channel['channel']."' where id=21";
$rs_update = mysql_query($query_update_setting, $hos) or die(mysql_error());

if(isset($_POST['queue'])&&($_POST['queue']=="Y")){
	$queue_con="'Y'";
	}
else if(!isset($_POST['queue'])){
	$queue_con="NULL";
	mysql_select_db($database_hos, $hos);
	$query_update_setting = "update ".$database_kohrx.".kohrx_recent_media set istatus=NULL where recent_media='qu'";
	$rs_update = mysql_query($query_update_setting, $hos) or die(mysql_error());

/*
	mysql_select_db($database_hos, $hos);
	$query_update_setting = "update kohrx_recent_media set istatus='Y' where recent_media='".$_POST['radio']."'";
	$rs_update = mysql_query($query_update_setting, $hos) or die(mysql_error());
*/
	}

if($_POST['radio']=="tv"){
	mysql_select_db($database_hos, $hos);
	$query_update_setting = "update ".$database_kohrx.".kohrx_recent_media set channel='".$_POST['channel_list']."' where recent_media='tv'";
	$rs_update = mysql_query($query_update_setting, $hos) or die(mysql_error());
}
else if($_POST['radio']=="yt"){
	mysql_select_db($database_hos, $hos);
	$query_update_setting = "update ".$database_kohrx.".kohrx_recent_media set channel='".$_POST['channel_list1']."' where recent_media='yt'";
	$rs_update = mysql_query($query_update_setting, $hos) or die(mysql_error());
}
else if($_POST['radio']=="sl"){
	mysql_select_db($database_hos, $hos);
	$query_update_setting = "update ".$database_kohrx.".kohrx_recent_media set channel='".$_POST['channel_list2']."' where recent_media='sl'";
	$rs_update = mysql_query($query_update_setting, $hos) or die(mysql_error());
}
else if($_POST['radio']=="mv"){
	mysql_select_db($database_hos, $hos);
	$query_update_setting = "update ".$database_kohrx.".kohrx_recent_media set channel='".$_POST['channel_list3']."' where recent_media='mv'";
	$rs_update = mysql_query($query_update_setting, $hos) or die(mysql_error());
}


mysql_free_result($rs_channel);

mysql_select_db($database_hos, $hos);
$query_update_setting = "update ".$database_kohrx.".kohrx_dispensing_setting set  value=".$queue_con." where id=33";
$rs_update = mysql_query($query_update_setting, $hos) or die(mysql_error());

	if($rs_update){
		echo "<script>parent.$.fn.colorbox.close();</script>";
		}
	}
mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting where id=23";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

mysql_select_db($database_hos, $hos);
$query_rs_setting2 = "select * from ".$database_kohrx.".kohrx_dispensing_setting where id=33";
$rs_setting2 = mysql_query($query_rs_setting2, $hos) or die(mysql_error());
$row_rs_setting2 = mysql_fetch_assoc($rs_setting2);
$totalRows_rs_setting2 = mysql_num_rows($rs_setting2);

mysql_select_db($database_hos, $hos);
$query_rs_channel1 = "select * from ".$database_kohrx.".kohrx_television_channel where istatus='Y' order by channel ASC";
$rs_channel1 = mysql_query($query_rs_channel1, $hos) or die(mysql_error());
$row_rs_channel1 = mysql_fetch_assoc($rs_channel1);
$totalRows_rs_channel1 = mysql_num_rows($rs_channel1);

mysql_select_db($database_hos, $hos);
$query_rs_tv = "select * from ".$database_kohrx.".kohrx_recent_media where recent_media='tv'";
$rs_tv = mysql_query($query_rs_tv, $hos) or die(mysql_error());
$row_rs_tv = mysql_fetch_assoc($rs_tv);
$totalRows_rs_tv = mysql_num_rows($rs_tv);

mysql_select_db($database_hos, $hos);
$query_rs_yt = "select * from ".$database_kohrx.".kohrx_recent_media where recent_media='yt'";
$rs_yt = mysql_query($query_rs_yt, $hos) or die(mysql_error());
$row_rs_yt = mysql_fetch_assoc($rs_yt);
$totalRows_rs_yt = mysql_num_rows($rs_yt);

mysql_select_db($database_hos, $hos);
$query_rs_channel2 = "select * from ".$database_kohrx.".kohrx_youtube_channel where istatus='Y' order by channel ASC";
$rs_channel2 = mysql_query($query_rs_channel2, $hos) or die(mysql_error());
$row_rs_channel2 = mysql_fetch_assoc($rs_channel2);
$totalRows_rs_channel2 = mysql_num_rows($rs_channel2);

mysql_select_db($database_hos, $hos);
$query_rs_channel3 = "select * from ".$database_kohrx.".kohrx_recent_media where recent_media='sl'";
$rs_channel3 = mysql_query($query_rs_channel3, $hos) or die(mysql_error());
$row_rs_channel3 = mysql_fetch_assoc($rs_channel3);
$totalRows_rs_channel3 = mysql_num_rows($rs_channel3);

mysql_select_db($database_hos, $hos);
$query_rs_channel4 = "select * from ".$database_kohrx.".kohrx_recent_media where recent_media='mv'";
$rs_channel4 = mysql_query($query_rs_channel4, $hos) or die(mysql_error());
$row_rs_channel4 = mysql_fetch_assoc($rs_channel4);
$totalRows_rs_channel4 = mysql_num_rows($rs_channel4);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<?php include('java_css_online.php'); ?>

<style>
/* Customize the label (the container) */
.container {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 16px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default checkbox */
.container input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}

/* Create a custom checkbox */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  border:solid 2px #0033CC;
  background-color: #eee;
}

/* On mouse-over, add a grey background color */
.container:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.container input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.container input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.container .checkmark:after {
  left: 9px;
  top: 5px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}
    ้html,body{
        overflow: hidden;
    }
</style>
<style>
/* The container */
.container2 {
    display: block;
    position: relative;
    padding-left: 35px;
    margin-bottom: 12px;
    cursor: pointer;
    font-size: 22px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

/* Hide the browser's default radio button */
.container2 input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

/* Create a custom radio button */
.checkmark2 {
    position: absolute;
    top: 30px;
    left: 0;
    height: 25px;
    width: 25px;
	  border:solid 2px #006699;
    background-color: #eee;
    border-radius: 50%;
}

/* On mouse-over, add a grey background color */
.container2:hover input ~ .checkmark2 {
    background-color: #ccc;
}

/* When the radio button is checked, add a blue background */
.container2 input:checked ~ .checkmark2 {
    background-color: #2196F3;
}

/* Create the indicator (the dot/circle - hidden when not checked) */
.checkmark2:after {
    content: "";
    position: absolute;
    display: none;
}

/* Show the indicator (dot/circle) when checked */
.container2 input:checked ~ .checkmark2:after {
    display: block;
}

/* Style the indicator (dot/circle) */
.container2 .checkmark2:after {
 	top: 9px;
	left: 9px;
	width: 8px;
	height: 8px;
	border-radius: 50%;
	background: white;
}
	html,body{
		overflow-x: hidden
	}
</style>
</head>

<body>
<div align="center">
<nav class="navbar navbar-dark bg-info text-white ">
  <!-- Navbar content -->
    <h4><i class="fas fa-tv" style="font-size: 20px;"></i>&ensp;ตั้งค่าจอแสดงผลเรียกคิว</h4>
</nav>
<form id="form1" name="form1" method="post" action="">
<div style="position: absolute; left: 50%;">	
<div style="padding:10px; left:-50%; width:850px;  position:relative" >
<div style="padding:10px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="68%"><img src="images/queue_display.png" width="527" height="323" /></td>
    <td width="32%">
      

    <label class="container2"><img src="images/tvonline2.png" width="110" height="89" align="absmiddle" />
  <input type="radio"  name="radio" id="radio" value="tv" <?php if (!(strcmp($row_rs_setting['value'],"tv"))) {echo "checked=\"checked\"";} ?>>
  <select name="channel_list" id="channel_list" class="inputcss1 font14" style="width:100px;">
    <?php
do {  
?>
    <option value="<?php echo $row_rs_channel1['channel_url']?>"<?php if (!(strcmp($row_rs_channel1['channel_url'], $row_rs_tv['channel']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_channel1['channel']?></option>
    <?php
} while ($row_rs_channel1 = mysql_fetch_assoc($rs_channel1));
  $rows = mysql_num_rows($rs_channel1);
  if($rows > 0) {
      mysql_data_seek($rs_channel1, 0);
	  $row_rs_channel1 = mysql_fetch_assoc($rs_channel1);
  }
?>
  </select>

  <span class="checkmark2"></span></label>
      
<label class="container2"><img src="images/youtube.png" width="111" height="74" align="absmiddle" />
  <input type="radio"  name="radio" id="radio" value="yt" <?php if (!(strcmp($row_rs_setting['value'],"yt"))) {echo "checked=\"checked\"";} ?>>
    <select name="channel_list1" id="channel_list1" class="inputcss1 font14" style="width:100px;">
      <?php
do {  
?>
      <option value="<?php echo $row_rs_channel2['channel_url']?>"<?php if (!(strcmp($row_rs_channel2['channel_url'], $row_rs_yt['channel']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_channel2['channel']?></option>
      <?php
} while ($row_rs_channel2 = mysql_fetch_assoc($rs_channel2));
  $rows = mysql_num_rows($rs_channel2);
  if($rows > 0) {
      mysql_data_seek($rs_channel2, 0);
	  $row_rs_channel2 = mysql_fetch_assoc($rs_channel2);
  }
?>
    </select>
  <span class="checkmark2"></span></label>
		
	<input type="button" class="btn btn-danger btn-sm" style="margin-left: 152px; margin-top: -10px;" onClick="window.location.href='youtube_manage_channel.php';" value="จัดการ" />

<label class="container2"><img src="images/slideshow.png" width="111" height="74" align="absmiddle" />
  <input type="radio" name="radio" id="radio" value="sl" <?php if (!(strcmp($row_rs_setting['value'],"sl"))) {echo "checked=\"checked\"";} ?>>
     
     <select name="channel_list2" id="channel_list2" class="inputcss1 font14" style="width:100px;">
	<?php 
	foreach(glob('picture/*', GLOB_ONLYDIR) as $dir) {
    $dir = str_replace('picture/', '', $dir); ?>
     <option value="<?php echo iconv('TIS-620','UTF-8',$dir); ?>" <?php if (!(strcmp($row_rs_channel3['channel'], $dir))) {echo "selected=\"selected\"";} ?> ><?php echo iconv('TIS-620','UTF-8',$dir); ?></option>
	<?
    }
	 ?>
    </select>

  <span class="checkmark2"></span></label>
  
  <label class="container2"><img src="images/movie.png" width="111" height="74" align="absmiddle" />
  <input type="radio"  name="radio" id="radio" value="mv" <?php if (!(strcmp($row_rs_setting['value'],"mv"))) {echo "checked=\"checked\"";} ?>>
    <select name="channel_list3" id="channel_list3" class="inputcss1 font14" style="width:100px;">
	<?php 
	foreach(glob('video/*', GLOB_ONLYDIR) as $dir) {
    $dir = str_replace('video/', '', iconv('TIS-620','UTF-8',$dir)); ?>
     <option value="<?php echo iconv('TIS-620','UTF-8',$dir); ?>" <?php if (!(strcmp($row_rs_channel4['channel'], $dir))) {echo "selected=\"selected\"";} ?> ><?php echo iconv('TIS-620','UTF-8',$dir); ?></option>
	<?
	}
	 ?>
    </select>

  <span class="checkmark2"></span></label>
</td>
  </tr>
</table>
<div style="position:relative; top:-300px; left:-30%; width:200px; height:50px;" align="center" class="thfont"><label class="container thfont font14" >เลือกจอแสดงคิว
  <input type="checkbox" name="queue" id="queue" value="Y" <?php if (!(strcmp($row_rs_setting2['value'],"Y"))) {echo "checked=\"checked\"";} ?>>
  <span class="checkmark"></span>
</label>
</div>
</div>
</div>
</div>
<input name="save" id="save"  style="background-color:#666; padding-bottom:5px; color:#FFF; border:0px; position: absolute; right: 35px; top: 12px;" class="btn btn-primary thfont font_bord font20" type="submit" value="บันทึก" />
</form>
</div>
</body>
</html>
<?php
mysql_free_result($rs_setting);


mysql_free_result($rs_channel1);


mysql_free_result($rs_tv);

mysql_free_result($rs_yt);

mysql_free_result($rs_channel2);

mysql_free_result($rs_channel3);

mysql_free_result($rs_channel4);

?>
