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
if($_GET['export']=="Y"){
$strExcelFileName="ระบบรายงานบันทึกอุบัติการณ์การสั่งยาผิดจำนวน.xls";
header("Content-Type: application/x-msexcel; name=\"$strExcelFileName\"");
header("Content-Disposition: inline; filename=\"$strExcelFileName\"");
header("Pragma:no-cache");
}
if(isset($_POST['do'])&&($_POST['do']!="")){
$do=$_POST['do'];
}
if(isset($_GET['do'])&&($_GET['do']!="")){
$do=$_GET['do'];
}

if(isset($_POST['date1'])&&($_POST['date1']!="")){
$date1=$_POST['date1'];
}
if(isset($_GET['date1'])&&($_GET['date1']!="")){
$date1=$_GET['date1'];
}
if(isset($_POST['date2'])&&($_POST['date2']!="")){
$date2=$_POST['date2'];
}
if(isset($_GET['date2'])&&($_GET['date2']!="")){
$date2=$_GET['date2'];
}
if(isset($_POST['s_totalcost'])&&($_POST['s_totalcost']!="")){
$s_totalcost=$_POST['s_totalcost'];
}
if(isset($_GET['s_totalcost'])&&($_GET['s_totalcost']!="")){
$s_totalcost=$_GET['s_totalcost'];
}
if($do=="search"){
	
	if($s_totalcost==2){ $condition=" and op.qty< d.qty";}
	if($s_totalcost==3){ $condition=" and op.qty> d.qty";}

mysql_select_db($database_hos, $hos);
$query_rs_usage = "select concat(p.pname,p.fname,'  ',p.lname) as patientname,d.hn,v.vstdate,d.drugusage,c.name,d.qty,d.qtyideal,d.appdate,concat(i.name,' ',i.strength) as drugname,op.qty as opqty,i.unitcost from  ".$database_kohrx.".kohrx_drugqty_check_record d left outer join vn_stat v on v.vn=d.vn left outer join patient p on p.hn=d.hn left outer join doctor c on c.code=d.doctorcode left outer join drugitems i on i.icode=d.icode left outer join drugusage u on u.shortlist=d.drugusage left outer join opitemrece op on op.icode=d.icode and op.vn=d.vn and op.drugusage=u.drugusage where v.vstdate between '".$date1."' and '".$date2."'".$condition;
$rs_usage = mysql_query($query_rs_usage, $hos) or die(mysql_error());
$row_rs_usage = mysql_fetch_assoc($rs_usage);
$totalRows_rs_usage = mysql_num_rows($rs_usage);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php if(!isset($_GET['export'])&&($_GET['export']!="Y")){ ?>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<?php } ?>
</head>

<body>
<?php if ($totalRows_rs_usage > 0) { // Show if recordset not empty ?>
<?php if(!isset($_GET['export'])&&($_GET['export']!="Y")){ ?>
<a href="drugqty_check_list.php?export=Y&do=search&date1=<?php echo $date1; ?>&date2=<?php echo $date2; ?>&s_totalcost=<?php echo $s_totalcost; ?>" class="thsan-light" style="text-decoration:none" target="_blank"><div class=" btn btn-success" style="margin-bottom:10px;"><img src="images/excel.png" width="16" height="16" align="absmiddle" /> ส่งออก Excell
</div></a>
<?php } ?>
<table  border="0" cellspacing="0" cellpadding="2" style="border-bottom:solid 1px #666666" class="table table-striped table-bordered" id="table">
<thead>
  <tr>
    <td align="center" >ลำดับ</td>
    <td align="center" >วันที่</td>
    <td align="center" >HN</td>
    <td align="center" >ชื่อ</td>
    <td align="center" >ชื่อยา</td>
    <td align="center" >วิธีใช้ (code)</td>
    <td align="center" >จำนวน(สั่ง/ควรได้)</td>
    <td align="center" >รับจริง</td>
    <td align="center" >ประหยัด</td>
    <td align="center" >จำนวนวันนัด</td>
    <td align="center" >ผู้สั่งใช้</td>
  </tr>
 </thead>
 <tbody>
     <?php $i=0; $totalcost=0; do { $i++;  
	     if($bgcolor=="#FFFFFF") { $bgcolor="#F2F2F2"; $font="#FFFFFF"; } else { $bgcolor="#FFFFFF"; $font="#999999"; }
		 
	if($row_rs_usage['opqty']!= NULL||$row_rs_usage['opqty']!=0){ 
	$costsave=($row_rs_usage['qty']-$row_rs_usage['opqty'])*$row_rs_usage['unitcost']; 
	
	if($s_totalcost==1){$costsave=$costsave;} 
	if($s_totalcost==2){ if($costsave<=0){$costsave=0;} else {$costsave=$costsave;}}
	
	if($s_totalcost==3){if($costsave>0){$costsave=0;}else {$costsave=$costsave;}}
	}
		if($row_rs_usage['opqty']== NULL||$row_rs_usage['opqty']==0){ $costsave=0;}
		$totalcost += $costsave;
	  ?> 
    <tr class="grid2">
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $i; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_usage['vstdate']; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_usage['hn']; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_usage['patientname']; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_usage['drugname']; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_usage['drugusage']; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_usage['qty']."/".$row_rs_usage['qtyideal']; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo "$row_rs_usage[opqty]"; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $costsave; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_usage['appdate']; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_usage['name']; ?></td>
  </tr>
<?php } while ($row_rs_usage = mysql_fetch_assoc($rs_usage)); ?>
</tbody>
</table>
<?php } ?>
<span class="table_head_small">ยอดการประหยัดยา =</span> <span class="big_red16"><?php echo $totalcost; ?> บาท</span>
</body>
</html>
<?php
mysql_free_result($rs_usage);
?>
