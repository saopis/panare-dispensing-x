<?
ob_start();
session_start();
?>
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


if($_GET['action']=="history"){
include('include/function.php');
}
?>
<?php require('include/get_channel.php'); ?>

<?php
mysql_select_db($database_hos, $hos);
$query_rs_visit_list = "SELECT v.vn,concat(DATE_FORMAT(v.vstdate,'%d/%m/'),(substring(v.vstdate,1,4)+543)) as date1,o.vsttime,v.pdx,i.name,p.name as pttype_name,d.name as doctor_name,d2.name as pay_staff_name,v.hn,o.oqueue,v.vstdate,count(p.icode) as rx,r.pay,r.pay_staff,r.rx_time FROM vn_stat v left outer join icd101 i on i.code=v.pdx left outer join pttype p on p.pttype=v.pttype left outer join ovst o on o.vn=v.vn left outer join doctor d on d.code=o.doctor left outer join (select icode,hn,vn from opitemrece where hn='".$_GET['hn']."' and icode like '1%' union all select icode,hn,vn from opitemrece_arc where hn='".$_GET['hn']."' and icode like '1%') as p on p.vn=v.vn left outer join rx_operator r on r.vn=v.vn and r.pay='Y' left outer join doctor d2 on d2.code=r.pay_staff WHERE v.hn='".$_GET['hn']."' group by v.vn order by v.vstdate DESC";
$rs_visit_list = mysql_query($query_rs_visit_list, $hos) or die(mysql_error());
$row_rs_visit_list = mysql_fetch_assoc($rs_visit_list);
$totalRows_rs_visit_list = mysql_num_rows($rs_visit_list);
if(isset($_SESSION['doctor_type'])&&($_SESSION['doctor_type']=="5")){ $finance= "_f"; $function_finance=",'income.php','form2'"; }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php //include('java_css_file.php'); ?>

<script>
$(document).ready(function() {
    closeNav();
});
function setInput(vstdate){
	$('#vstdate').val(vstdate);
	}
</script>
</head>

<body>
<input type="hidden" id="hidden_hn" value="<?php echo $_GET['hn']; ?>"/>
<div class="text-center bg-primary text-white rounded-top" style=" padding:10px; margin-left: 10px; margin-right: 10px;"><i class="fas fa-prescription font20"></i><?php if($_GET['action']=="history"){ echo "&ensp;ประวัติการเข้ารับบริการและประวัติการจ่ายยา"; } else { echo "&ensp;ผู้ป่วยไม่ได้รับบริการในวันที่ ".date('d/m/').(date('Y')+543); } ?> กรุณาเลือกวันที่ผู้ป่วยมารับบริการจากรายการข้างล่าง</div>
<div style="margin-left: 10px; margin-right: 10px;" >
  <table border="0" cellpadding="3" cellspacing="0" class="table table-sm thfont font14 " style="width:100%">
  <thead class="">
    <tr class="text-center" >
      <th  align="center" bgcolor="#B6D3F3"><strong>ลำดับ</strong></th>
      <th  align="left" bgcolor="#B6D3F3"><strong>วันที่รับบริการ</strong></th>
      <th  align="center" bgcolor="#B6D3F3">เวลา</th>
      <th  align="center" bgcolor="#B6D3F3"><strong>สิทธิ์</strong></th>
      <th  align="left" bgcolor="#B6D3F3"><strong>วินิจฉัย</strong></th>
      <th  align="center" bgcolor="#B6D3F3">จำนวนรายการยา</th>
      <th  align="center" bgcolor="#B6D3F3">จ่ายยา</th>
      <th  align="center" bgcolor="#B6D3F3">ผู้จ่าย</th>
    </tr>
    </thead>
    <tbody>
    <?php $i=0; do { $i++; 
	?>
    <tr class="grid5" style="cursor:pointer" onclick="detail_load_vn('<?php echo $row_rs_visit_list['vn']; ?>','<?php echo $row_rs_visit_list['hn']; ?>','<?php echo date_db2th($row_rs_visit_list['vstdate']); ?>');vstdate_fill('<?php echo date_db2th($row_rs_visit_list['vstdate']); ?>');<?php if($row_channel['cursor_position']=='queue'){ echo "q_fill('".$row_rs_visit_list['oqueue']."');"; } else if($row_channel['cursor_position']=='hn_search'){ echo "hn_fill('".$row_rs_visit_list['hn']."');"; } else { echo "fistFocus('hn'); hn_fill('".$row_rs_visit_list['hn']."');"; } ?>">
      <td  align="center" "><?php echo "<span class=\"badge badge-dark text-white font16\" style='width:35px;'>".$i."</span>"; ?></td>
      <td align="left" "><?php echo dateThai($row_rs_visit_list['vstdate']);  ?> </td>
      <td align="center" "><?php echo substr($row_rs_visit_list['vsttime'],0,5); ?></td>
      <td align="center" "><?php echo "$row_rs_visit_list[pttype_name]"; ?></td>
      <td align="left" "><?php if(!empty($row_rs_visit_list['pdx'])){ echo "($row_rs_visit_list[pdx])"."  "."$row_rs_visit_list[name]"; } ?></td>
      <td align="center" "><?php if($row_rs_visit_list['rx']==0){ echo ""; } else { echo $row_rs_visit_list['rx']; } ?></td>
      <td align="center" "><?php if($row_rs_visit_list['pay']=="Y"&&$row_rs_visit_list['pay_staff']<>NULL){ ?><i class="fas fa-check font20 text-primary"></i>&nbsp;<?php echo substr($row_rs_visit_list['rx_time'],0,5);  } ?></td>
      <td align="center" class="font12" "><nobr><?php echo $row_rs_visit_list['pay_staff_name']; ?></nobr></td>
    </tr>
    <?php } while($row_rs_visit_list = mysql_fetch_assoc($rs_visit_list)); ?>
    </tbody>
  </table>

</div>
</body>
</html>
<?php
mysql_free_result($rs_visit_list);
?>
