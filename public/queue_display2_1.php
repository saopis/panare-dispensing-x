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
$query_channel = "SELECT patient_picture from ".$database_kohrx.".kohrx_queue_caller_channel q left outer join ".$database_kohrx.".kohrx_queue_caller_channel_name n on n.id=q.channel WHERE ip='".$get_ip."'";
$channel = mysql_query($query_channel, $hos) or die(mysql_error());
$row_channel = mysql_fetch_assoc($channel);
$totalRows_channel = mysql_num_rows($channel);

$pt_show=$row_channel['patient_picture'];

mysql_free_result($channel);

mysql_select_db($database_hos, $hos);
$query_rs_list = "SELECT t1.patient_name,t1.hn,t1.patient_name,t1.call_datetime,q.queue,n.channel_name,n.q_show
FROM ".$database_kohrx.".kohrx_queue_caller_list t1
INNER JOIN
(
    SELECT channel_id, MAX(call_datetime) AS max_date
    FROM ".$database_kohrx.".kohrx_queue_caller_list  
    GROUP BY channel_id
) t2
    ON t1.channel_id = t2.channel_id AND t1.call_datetime = t2.max_date
left outer join ".$database_kohrx.".kohrx_queued q on q.hn=t1.hn and substr(q.queue_datetime,1,10)= CURDATE() and q.room_id=t1.room_id
left outer join ".$database_kohrx.".kohrx_queue_caller_channel_name n on n.id=t1.channel_id
where t1.room_id='".$_GET['room_id']."' and TIME_TO_SEC(TIMEDIFF(CURTIME(),SUBSTR(t1.call_datetime,11,9))) <18000 and t1.dispensed is NULL and SUBSTR(t1.call_datetime,1,10)=CURDATE() group by t1.hn order by t1.call_datetime DESC limit 4";
echo $query_rs_list;
$rs_list = mysql_query($query_rs_list, $hos) or die(mysql_error());
$row_rs_list = mysql_fetch_assoc($rs_list);
$totalRows_rs_list = mysql_num_rows($rs_list);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js|https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.min.js
"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
<!-- CSS -->
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.bootstrap4.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.1/css/select.bootstrap4.min.css"/>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>

<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.img-circle {
    border-radius: 5%;
}
</style>
<style>
.top, .bot {
    height: 50%;
    border: 1px solid black;
    box-sizing: border-box;
}

.left, .right {
    display: inline-block;
    width: 50%;
    height: 100%;
    margin-right: -4px;
    border: 1px solid red;
    box-sizing: border-box;
}

html, body {
    height: 100%;
    padding: 0;
    margin: 0;
}
</style>

</head>

<body>
<div class="top">
   <div class="left">img</div>
   <div class="right">txt</div>
</div>

<!-- BOT 50% -->
<div class="bot">
   <div class="left">text</div>
   <div class="right">img</div>
</div>
</body>
</html>
<?php
mysql_free_result($rs_list);
?>
