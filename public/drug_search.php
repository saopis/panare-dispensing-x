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

	mysql_select_db($database_hos, $hos);
    $sql = "
	select icode,concat(name,' ',strength) as drugname from drugitems where locate('".$q."',name ) > 0 and istatus='Y' order by name ASC limit $pagesize ";
$qr=mysql_query($sql, $hos) or die(mysql_error());
$total=mysql_num_rows($qr);
while($rs=mysql_fetch_array($qr)) {
    $json_data[]=array(  
        "id"=>$rs['icode'],  
        "label"=>$rs['drugname'],  
        "value"=>$rs['drugname'],  
    );  
}  
$json= json_encode($json_data);  
echo $json;  
mysql_close();  
exit;
	

?>