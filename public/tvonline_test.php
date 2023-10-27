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
$query_rs_channel = "select * from kohrx_recent_media where recent_media ='mv'";
$rs_channel = mysql_query($query_rs_channel, $hos) or die(mysql_error());
$row_rs_channel = mysql_fetch_assoc($rs_channel);
$totalRows_rs_channel = mysql_num_rows($rs_channel);


?>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="images/favicon2.ico" />
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="refresh" content="300" />
  <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
	<link rel="stylesheet" href="//releases.flowplayer.org/7.2.6/skin/skin.css">
  <script src="https://releases.flowplayer.org/7.2.6/flowplayer.min.js"></script>
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

<div class="flowplayer fp-slim" fp-custom-playlist data-autoplay="true" data-loop="true" data-shuffledlist="true"   >
    <video>
	<source type="video/mp4"
              src="video/white.mp4">
    </video>

    <div class="fp-playlist">
    <a href="video/white.mp4"></a>
<?php
$dir = 'video/'.$row_rs_channel['channel'];
$file_display = array ('mp4','jpg', 'jpeg', 'png', 'gif');

if (file_exists($dir) == false) {
    echo 'Directory \'', $dir, '\' not found';
} else {
    $dir_contents = scandir($dir);
    shuffle($dir_contents);

    foreach ($dir_contents as $file) {
        $file_type = strtolower(end(explode('.', $file)));

        if ($file !== '.' && $file !== '..' && in_array($file_type, $file_display) == true) {
            //echo '<img class="photo" src="', $dir, '/', $file, '" alt="', $file, '" />';
      	?>
		<a href="<?php echo $dir."/".$file; ?>"></a>
        <?php
        }
    }
}

?>
        
   </div>  
   <a class="fp-prev"></a>
   <a class="fp-next"></a>
</div>   

</body>
</html>