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

if($_GET['type']=="drugname"){
	mysql_select_db($database_hos, $hos);
    $sql = "
	select concat(name,' ',strength) as drugname from s_drugitems d	where locate('".$q."',d.name ) > 0 and istatus='Y' and icode like '1%' order by name ASC limit $pagesize ";
$qr=mysql_query($sql, $hos) or die(mysql_error());
$total=mysql_num_rows($qr);
while($rs=mysql_fetch_array($qr)) {
    $json_data[]=array(  
        "id"=>$rs['drugname'],  
        "label"=>$rs['drugname'],  
        "value"=>$rs['drugname'],  
    );
	  
}  
$json= json_encode($json_data);  
echo $json;  
mysql_close();  
}
if($_GET['type']=="drugusage"){
	mysql_select_db($database_hos, $hos);
    $sql = "
	select shortlist from drugusage where locate('".$q."',code ) > 0 and status='Y' order by code ASC limit $pagesize ";
$qr=mysql_query($sql, $hos) or die(mysql_error());
$total=mysql_num_rows($qr);
while($rs=mysql_fetch_array($qr)) {
    $json_data[]=array(  
        "id"=>$rs['shortlist'],  
        "label"=>$rs['shortlist'],  
        "value"=>$rs['shortlist'],  
    );  
}  
$json= json_encode($json_data);  
echo $json;  
mysql_close();  

}
if($_GET['type']=="source"){
	mysql_select_db($database_hos, $hos);
    $sql = "
	select concat(hosptype,name) as hospname from hospcode where locate('".$q."',name ) > 0 order by name ASC limit $pagesize ";
$qr=mysql_query($sql, $hos) or die(mysql_error());
$total=mysql_num_rows($qr);
while($rs=mysql_fetch_array($qr)) {
    $json_data[]=array(  
        "id"=>$rs['hospname'],  
        "label"=>$rs['hospname'],  
        "value"=>$rs['hospname'],  
    );  
}  
$json= json_encode($json_data);  
echo $json;  
mysql_close();  

}
?>