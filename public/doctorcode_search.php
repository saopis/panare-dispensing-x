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
?>  
  
<?php

$pagesize = 20; // จำนวนรายการที่ต้องการแสดง
    $q = $_GET["term"];

	if($_GET['type']=="doctorcode"){
	mysql_select_db($database_hos, $hos);
    $sql = "
	select code,name from doctor where locate('".$q."',name ) > 0 and code not in (select doctorcode from  ".$database_kohrx.".kohrx_rx_person where active='Y') order by name ASC limit $pagesize ";
$qr=mysql_query($sql, $hos) or die(mysql_error());
$total=mysql_num_rows($qr);
while($rs=mysql_fetch_array($qr)) {
    $json_data[]=array(  
        "id"=>$rs['code'],  
        "label"=>$rs['name'],  
        "value"=>$rs['name'],  
    );  
}  
$json= json_encode($json_data);  
echo $json;  
mysql_close();  
exit;
	}

	if($_GET['type']=="usersetting"){
	mysql_select_db($database_hos, $hos);
    $sql = "
	SELECT code,name FROM doctor WHERE  locate('".$q."',name ) > 0 and active='Y' and code not in (select doctorcode from ".$database_kohrx.".kohrx_user_setting) ORDER BY name ASC limit $pagesize ";
$qr=mysql_query($sql, $hos) or die(mysql_error());
$total=mysql_num_rows($qr);
while($rs=mysql_fetch_array($qr)) {
    $json_data[]=array(  
        "id"=>$rs['code'],  
        "label"=>$rs['name'],  
        "value"=>$rs['name'],  
    );  
}  
$json= json_encode($json_data);  
echo $json;  
mysql_close();  
exit;
	}
?>