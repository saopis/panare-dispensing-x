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
	$curdate=date('Y-m-d');
	include('include/function.php');

	if(isset($_GET['queue'])&&$_GET['queue']!=""){
mysql_select_db($database_hos, $hos);
$query_rs_ptnote = "SELECT a.* FROM ptnote a left outer join ovst ov on ov.hn=a.hn WHERE ov.vstdate = '".$curdate."' and ov.oqueue='".$_GET['queue']."' and noteflag like '%RX%' and (expire_date is null or expire_date >= '".$curdate."') order by a.vstdate DESC ";
	}
	if(isset($an)&&$an!=""){
mysql_select_db($database_hos, $hos);
$query_rs_ptnote = "
SELECT a.* from ptnote a left join an_stat aa on aa.hn=a.hn where a.noteflag like '%RX%' and substring(aa.regdate,1,7)=substring('".$curdate."',1,7) and aa.an='".$_GET['an']."' and (expire_date is null or expire_date >= '".$curdate."') order by a.vstdate DESC";
	}

	if(isset($hn)&&$hn!=""){
mysql_select_db($database_hos, $hos);
$query_rs_ptnote = "SELECT a.* FROM ptnote a where hn='".$_GET['hn']."' and noteflag like '%RX%' and (expire_date is null or expire_date >= '".$curdate."') order by vstdate DESC";
		//echo $query_rs_ptnote;
	}
$rs_ptnote = mysql_query($query_rs_ptnote, $hos) or die(mysql_error());
$row_rs_ptnote = mysql_fetch_assoc($rs_ptnote);
$totalRows_rs_ptnote = mysql_num_rows($rs_ptnote);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>    

<style type="text/css">	
html,body {
background-color: #CCCCCC;
overflow-x: hidden;
}
.table td{
    border: black solid 1px !important;
}	
.table th{
    border: black solid 1px !important;
}	
</style>
</head>

<body>
<table style="width: 100%" class="table table-hover table-bordered thfont table-sm font14">
  <thead class="bg-dark text-white">
	<tr>
    <th  class="text-center" >วันที่</th>
    <th  class="text-center">ข้อความ</th>
    <th  class="text-center" >ผู้บันทึก</th>

  </tr>
	</thead>	
	<tbody class="bg-white">
 <?php do { ?> 
	<tr>
    
      <td  class="text-center" ><?php echo dateThai($row_rs_ptnote['note_datetime']); ?></td>
      <td ><?php if($row_rs_ptnote['plain_text']<>""){ echo $row_rs_ptnote['plain_text']; } else { echo $row_rs_ptnote['ptnote']; } ?></td>
      <td  class="text-center"><?php echo $row_rs_ptnote['note_staff']; ?></td>
      
  </tr>
   <?php } while ($row_rs_ptnote = mysql_fetch_assoc($rs_ptnote)); ?>
	</tbody>	
</table>
<br />
</body>
</html>
<?php
mysql_free_result($rs_ptnote);
?>
