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




if(isset($_POST['do'])&&($_POST['do']!="")){
$do=$_POST['do'];
}
if(isset($_GET['do'])&&($_GET['do']!="")){
$do=$_GET['do'];
}

if($do=="search"){

mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

mysql_free_result($rs_setting);
	
if(isset($_POST['datestart'])&&($_POST['datestart']!="")){
$date1=$_POST['datestart'];
}
if(isset($_GET['datestart'])&&($_GET['datestart']!="")){
$date1=$_GET['datestart'];
}
if(isset($_POST['dateend'])&&($_POST['dateend']!="")){
$date2=$_POST['dateend'];
}
if(isset($_GET['dateend'])&&($_GET['dateend']!="")){
$date2=$_GET['dateend'];
}

if(isset($_GET['drug'])&&($_GET['drug']!="")){
	$condition.=" and icode='".$_GET['drug']."'";
}
	
if(isset($_GET['drugusage'])&&($_GET['drugusage']!="")){
	$condition.=" and u.drugusage='".$_GET['drugusage']."'";
}
	
if(isset($_GET['logs'])&&($_GET['logs']!="")){
	if($_GET['logs']=="1"){
		$condition.=" and change_type='new' ";
	}
	if($_GET['logs']=="2"){
		$condition.=" and change_type='off' ";
	}
	if($_GET['logs']=="3"){
		$condition.=" and change_type='change' ";
	}
	if($_GET['logs']=="4"){
		$condition.=" and change_type='up' ";
	}
	if($_GET['logs']=="5"){
		$condition.=" and change_type='down' ";
	}
}
if($_GET['hn']!=""){
	$condition.=" and v.hn =LPAD('".$_GET['hn']."','".$row_setting[24]."','0')";
	}
	

	
include('include/function.php');
include('include/function_sql.php');
mysql_select_db($database_hos, $hos);
$query_rs_couselling = "select v.vstdate,v.hn,u.icode,s.shortlist,u.change_type from ".$database_kohrx.".kohrx_drug_use_change u left outer join vn_stat v on v.vn=u.vn left outer join drugusage s on s.drugusage=u.drugusage where v.vstdate between '".$date1."' and '".$date2."' ".$condition;
//echo $query_rs_couselling;
$rs_couselling = mysql_query($query_rs_couselling, $hos) or die(mysql_error());
$row_rs_couselling = mysql_fetch_assoc($rs_couselling);
$totalRows_rs_couselling = mysql_num_rows($rs_couselling);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
	<link href="include/kohrx/css/kohrx.css" rel="stylesheet" type="text/css" />

<?php include('include/bootstrap/datatable_report.php'); ?>
<script>
	
function indicator_hide(){
	$('#indicator').hide();
}
</script>

</head>

<body>
<?php if($totalRows_rs_couselling<>0){ ?>
<table  id="example" class="table table-striped table-bordered table-hover table-sm " style="width:100%; font-size:14px" >  
	<thead>
  <tr >
    <td width="2%" height="22" align="center">no.</td>
    <td width="6%" align="center">วันที่</td>
    <td width="7%" align="center">HN</td>
    <td width="10%" align="center">ชื่อ</td>
    <td width="19%" align="center">รายการยา</td>
    <td width="8%" align="center">วิธีใช้</td>
    <td width="5%" align="center">log type</td>
  </tr>
	</thead>
	<tbody>
   <?php $i=0; do { $i++; 
   switch ($row_rs_couselling['result']){
	   case 1 :
	   $result="ทำได้";
	   break;
	   case 2 :
	   $result="ทำได้บ้าง";
	   break;
	   case 3 :
	   $result="ทำไม่ได้";
	   break;
	   }
   ?><tr  >
      <td align="center" valign="top"><?php echo $i; ?></td>
      <td align="center" valign="top"><?php echo date_db2th($row_rs_couselling['vstdate']); ?></td>
      <td align="center" valign="top"><?php echo "$row_rs_couselling[hn]"; ?></td>
      <td align="center" valign="top"><nobr><?php echo ptname($row_rs_couselling['hn']); ?></nobr></td>
      <td align="center" valign="top"><?php echo drugname($row_rs_couselling['icode']);  ?></td>
      <td align="center" valign="top"><?php echo $row_rs_couselling['shortlist']; ?></td>
      <td align="center" valign="top"><?php echo $row_rs_couselling['change_type']; ?></td>
      </tr> 
  <?php } while ($row_rs_couselling = mysql_fetch_assoc($rs_couselling)); ?>
		</tbody>
</table>
<?php } else{ ?>
<div style="padding: 20px;" class="font20"><i class="far fa-times-circle font20"></i>&ensp;ไม่พบรายการที่ค้นหา</div>
<?php } ?>    

</body>
</html>
<?php
echo "<script> indicator_hide();</script>";
mysql_free_result($rs_couselling);
?>
