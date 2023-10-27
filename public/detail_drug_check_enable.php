<?php require_once('Connections/hos.php'); ?>
<?php require('include/get_channel.php'); ?>
<?php if(isset($_GET['sound'])){
    if($_GET['sound']=="Y"){
  		mysql_select_db($database_hos, $hos);
        $query_update = "update ".$database_kohrx.".kohrx_queue_caller_channel set detail_drug_sound='Y' where ip='".$get_ip."'";
        $update = mysql_query($query_update, $hos) or die(mysql_error());				
    }
    if($_GET['sound']=="N"){
  		mysql_select_db($database_hos, $hos);
        $query_update = "update ".$database_kohrx.".kohrx_queue_caller_channel set detail_drug_sound=NULL where ip='".$get_ip."'";
        $update = mysql_query($query_update, $hos) or die(mysql_error());				
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
</body>
</html>