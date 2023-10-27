<?php require_once('../Connections/hos.php'); ?>
<?php
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

function getMonthDays($month, $year)
{
    return $month == 2
        ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29)))
        : (($month - 1) % 7 % 2 ? 30 : 31)
    ;
}

?>
<?php
	$explode = explode("?", curPageURL());
	include('../include/function.php');
	include('include/function.php');

?>
<? 
if(isset($_GET['id'])){
$ym=substr($date_delete,0,7);
$d=substr($date_delete,8,2);

mysql_select_db($database_hos, $hos);
$delete = "delete from ".$database_kohrx.".kohrx_med_error_indiv2 where id='".$_GET['id']."'";
$qdelete = mysql_query($delete, $hos) or die(mysql_error());

mysql_select_db($database_hos, $hos);
$update = "update ".$database_kohrx.".kohrx_med_error_indiv set name".$person." = name".$person."-1 where date_error='$ym' and date_error2='$d'";
$qupdate = mysql_query($update, $hos) or die(mysql_error());
}
?>
<? 
if($pttype!="all"){
	$condition1=" and m.pttype='$pttype' ";
	}

if($type==""){ 
$condition="m.date_error='$date_error' and m.doctor_code='$who'";}
else if($type=="2"){  
mysql_select_db($database_hos, $hos);
$query_countdate = "select id from ".$database_kohrx.".kohrx_med_error_indiv where date_error='$date_error'";
$countdate = mysql_query($query_countdate, $hos) or die(mysql_error());
$row_countdate = mysql_fetch_assoc($countdate);
$totalRows_countdate = mysql_num_rows($countdate);


$startdate=$date_error;
$enddate=$date_error;

$condition="substring(m.date_error,1,7) ='$startdate' and m.doctor_code='$who' "; mysql_free_result($countdate);}
else if($type=="3"){
$condition="m.date_error = '$date_error'"; 
}
else if($type=="4"){  
mysql_select_db($database_hos, $hos);
$query_countdate = "select id from ".$database_kohrx.".kohrx_med_error_indiv where date_error='$date_error'";
$countdate = mysql_query($query_countdate, $hos) or die(mysql_error());
$row_countdate = mysql_fetch_assoc($countdate);
$totalRows_countdate = mysql_num_rows($countdate);

$startdate=$date_error."-01";
$date11=explode('-',$date_error);
$enddate=$date_error."-".getMonthDays($date11[1],$date11[0]);


$condition="m.date_error between '$startdate' and '$enddate' "; 
mysql_free_result($countdate);}

?>

<?php
mysql_select_db($database_hos, $hos);
$query_error = "SELECT m.id,m.date_error,p.doctorcode as pid,p.doctorcode,t.name,m.drug1,m.drug2 FROM ".$database_kohrx.".kohrx_med_error_indiv2 m left outer join ".$database_kohrx.".kohrx_rx_person p on p.doctorcode=m.doctor_code left outer join ".$database_kohrx.".kohrx_med_error_error_cause t on t.id=m.error_type  WHERE ".$condition.$condition1." and p.doctorcode !='' ORDER BY m.date_error ASC";
$error = mysql_query($query_error, $hos) or die(mysql_error());
$row_error = mysql_fetch_assoc($error);
$totalRows_error = mysql_num_rows($error);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?> 

<style>
body {
	background-color: #FFFFFF;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
html,body { height:100%; overflow: hidden; }

::-webkit-scrollbar { width: 15px; }

::-webkit-scrollbar-track {
    box-shadow: inset 0 0 10px 10px #E6E6E6;
    border: solid 3px transparent;
}

::-webkit-scrollbar-thumb {
    box-shadow: inset 0 0 10px 10px #CCCCCC;
    border: solid 3px transparent;
}

</style>
</head>

<body>
<nav class="navbar navbar-light bg-secondary">
  <a class="navbar-brand text-white">ความคลาดเคลื่อนจากการจัดยา</a>
</nav>
<?php if ($totalRows_error > 0) { // Show if recordset not empty ?>
<div class="p-3" style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:90vh;" >
  <table width="100%" class="table table-bordered font14">
	 <thead class="bg-light">
    <tr>
      <td rowspan="2" align="center" >ลำดับ</td>
      <td rowspan="2" align="center" >วันที่</td>
      <td rowspan="2" align="center" >ชื่อ</td>
      <td rowspan="2" align="center" >ความผิดพลาด</td>
      <td colspan="3" align="center" >คู่ยา Lasa </td>
    </tr>
    <tr>
      <td align="center" >drug1</td>
      <td align="center" >drug2</td>
      <td align="center" >&nbsp;</td>
    </tr>
		</thead>
    <? $i=0; do{ $i++;


	mysql_select_db($database_hos, $hos);
$query_rs_drug1 = "SELECT concat(name,' ',strength) as drugname FROM drugitems d  WHERE icode='".$row_error['drug1']."'";
$rs_drug1 = mysql_query($query_rs_drug1, $hos) or die(mysql_error());
$row_rs_drug1 = mysql_fetch_assoc($rs_drug1);
$totalRows_rs_drug1 = mysql_num_rows($rs_drug1);

	mysql_select_db($database_hos, $hos);
$query_rs_drug2 = "SELECT concat(name,' ',strength) as drugname FROM drugitems d  WHERE icode='".$row_error['drug2']."'";
$rs_drug2 = mysql_query($query_rs_drug2, $hos) or die(mysql_error());
$row_rs_drug2 = mysql_fetch_assoc($rs_drug2);
$totalRows_rs_drug2 = mysql_num_rows($rs_drug2);

	 ?>
    <tr>
      
      <td align="center" ><? echo $i; ?></td>
        <td align="center" ><?php echo dateThai($row_error['date_error']); ?></td>
        <td align="center" ><?php echo doctorname($row_error['doctorcode']); ?></td>
        <td align="center" ><?php echo $row_error['name']; ?></td>
        <td align="center" ><?php if($row_rs_drug1['drugname']!=""){ echo $row_rs_drug1['drugname']; } else { echo "-"; } ?></td>
        <td align="center" ><?php if($row_rs_drug2['drugname']!=""){ echo $row_rs_drug2['drugname']; } else { echo "-"; } ?></td>
        <td align="center" ><a href="predis_error_detail.php?<? echo $explode[1]; ?>&amp;id=<?php echo $row_error['id']; ?>&amp;date_delete=<?php echo $row_error['date_error']; ?>&amp;person=<?php echo $row_error['pid']; ?>"><i class="fas fa-eraser font20"></i></a></td>
    </tr><?php } while ($row_error = mysql_fetch_assoc($error)); ?>
      </table>
</div>
	<?php
  mysql_free_result($rs_drug1);
   } // Show if recordset not empty ?>
</body>
</html>
<?php
mysql_free_result($error);


?>
