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
$table_db="drugusage"; // ตารางที่ต้องการค้นหา
$find_field="shortlist"; // ฟิลที่ต้องการค้นหา
if($_GET['term']!=""){
    $q = $_GET["term"];
	mysql_select_db($database_hos, $hos);
    $sql = "select * from $table_db 
    where  locate('$q', $find_field) > 0 and status='Y' 
    order by locate('$q', $find_field), $find_field limit $pagesize";
}else{
    $sql = "select * from $table_db  where 1 limit $pagesize";      
}
$qr=mysql_query($sql, $hos) or die(mysql_error());
$total=mysql_num_rows($qr);
while($rs=mysql_fetch_array($qr)) {
    $json_data[]=array(  
        "id"=>$rs['drugusage'],  
        "label"=>$rs['shortlist'],  
        "value"=>$rs['shortlist'],  
    );  
}  
$json= json_encode($json_data);  
echo $json;  
mysql_close();  
exit;
?>