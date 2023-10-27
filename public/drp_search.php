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

	if($_GET['type']=="drug"){
	mysql_select_db($database_hos, $hos);
    $sql = "
	select o.icode,concat(name,' ',strength) as drugname from opitemrece o left outer join s_drugitems d on d.icode=o.icode	where locate('".$q."',d.name ) > 0 and istatus='Y' and o.vn='".$_GET['vn']."' order by name ASC limit $pagesize ";
$qr=mysql_query($sql, $hos) or die(mysql_error());
$total=mysql_num_rows($qr);
while($rs=mysql_fetch_array($qr)) {
	if($rs['icode']==""){ $icode=""; }
	else if($rs['icode']!=""){ $icode=$rs['icode']; }
    $json_data[]=array(  
        "id"=>$icode,  
        "label"=>$rs['drugname'],  
        "value"=>$rs['drugname'],  
    );  
}  
$json= json_encode($json_data);  
echo $json;  
mysql_close();  
exit;
	}
	if($_GET['type']=="problem"){
	mysql_select_db($database_hos, $hos);
    $sql = "
	select * from drp_cause where locate('".$q."',drp_cause_name ) > 0  order by drp_cause_id ";
$qr=mysql_query($sql, $hos) or die(mysql_error());
$total=mysql_num_rows($qr);
while($rs=mysql_fetch_array($qr)) {
	if($rs['std_code']==""){ $std_code=""; }
	else if($rs['std_code']!=""){ $std_code=$rs['std_code']; }
    $json_data[]=array(  
        "id"=>$std_code,  
        "label"=>$rs['drp_cause_name'],  
        "value"=>$rs['drp_cause_name'],  
    );  
}  
$json= json_encode($json_data);  
echo $json;  
mysql_close();  
exit;
	}
	if($_GET['type']=="code"){
	mysql_select_db($database_hos, $hos);
    $sql = "
	select * from drp_cause where locate('".$q."',std_code ) > 0  order by std_code ";
$qr=mysql_query($sql, $hos) or die(mysql_error());
$total=mysql_num_rows($qr);
while($rs=mysql_fetch_array($qr)) {
	if($rs['drp_cause_name']==""){ $name=""; $name2=""; }
	else if($rs['drp_cause_name']!=""){ $name=$rs['drp_cause_name']; $name2=$rs['std_code'].":".$rs['drp_cause_name']; }
    $json_data[]=array(  
        "id"=>$name,  
        "label"=>$name2,  
        "value"=>$rs['std_code'],  
    );  
}  
$json= json_encode($json_data);  
echo $json;  
mysql_close();  
exit;
	}

if($_POST['id'])
{
$id=$_POST['id'];
mysql_select_db($database_hos, $hos);
$query_rs_problem = "select * from drp_cause WHERE std_code='".$id."'";
$rs_problem = mysql_query($query_rs_problem, $hos) or die(mysql_error());
$row_rs_problem = mysql_fetch_assoc($rs_problem);
$totalRows_rs_problem = mysql_num_rows($rs_problem);

echo $row_rs_problem['drp_cause_name'];

mysql_free_result($rs_problem);

}


?>