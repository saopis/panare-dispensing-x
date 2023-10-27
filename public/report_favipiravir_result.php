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

include('include/function.php');

mysql_select_db($database_hos, $hos);
$query_rs_config = "select * from opdconfig";
$rs_config = mysql_query($query_rs_config, $hos) or die(mysql_error());
$row_rs_config = mysql_fetch_assoc($rs_config);
$totalRows_rs_config = mysql_num_rows($rs_config);

$date1=date_th2db($_POST['date1']);
$date2=date_th2db($_POST['date2']);

$remain=$_POST['remain'];

mysql_select_db($database_hos, $hos);
if($_POST['depart']=="IPD"){
$query_rs_result = "select ord.rxdate,ord.rxtime,concat(pt.pname,pt.fname,' ',pt.lname) as ptname,pt.cid,sum(o.qty) as sumqty,u.name1,u.name2,u.name3,s.name1 as sname1,s.name2 as sname2,s.name3 as sname3 from opitemrece o left outer join ipt_order_no ord on ord.order_no=o.order_no left outer join patient pt on pt.hn=o.hn left outer join drugusage u on u.drugusage=o.drugusage left outer join sp_use s on s.sp_use=o.sp_use where concat(ord.rxdate,' ',ord.rxtime) between '".$date1." ".$_POST['time1'].":00' and '".$date2." ".$_POST['time2'].":59'  and o.icode='1640020'  group by o.hn,ord.rxdate order by ord.rxdate,ord.rxtime,o.hn";
}
else {
$query_rs_result="
select o.rxdate,o.rxtime,concat(pt.pname,pt.fname,' ',pt.lname) as ptname,pt.cid,sum(o.qty) as sumqty,u.name1,u.name2,u.name3,s.name1 as sname1,s.name2 as sname2,s.name3 as sname3 from opitemrece o left outer join patient pt on pt.hn=o.hn left outer join drugusage u on u.drugusage=o.drugusage left outer join sp_use s on s.sp_use=o.sp_use where concat(o.rxdate,' ',o.rxtime) between '".$date1." ".$_POST['time1'].":00' and '".$date2." ".$_POST['time2'].":59'  and o.icode='1640020' and o.vn is not NULL  group by o.hn,o.rxdate order by o.rxdate,o.rxtime,o.hn
";
}
$rs_result = mysql_query($query_rs_result, $hos) or die(mysql_error());
$row_rs_result = mysql_fetch_assoc($rs_result);
$totalRows_rs_result = mysql_num_rows($rs_result);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php include('include/bootstrap/datatable_report.php'); ?>
    
</head>

<body>
<table  id="example" class="table table-striped table-bordered table-hover table-sm " style="width:100%; font-size:12px" >  
    <thead>
    <tr>
      <td align="center" >รหัสแม่ข่าย</td>
      <td align="center" >วัน/เดือน/ปี</td>
      <td align="center" >เวลาจ่าย</td>
      <td align="center" >รับ/จ่าย</td>
      <td align="center" >รหัส</td>
      <td align="center" >หน่วยงาน</td>
      <td align="center" >จำนวน</td>
      <td align="center" >คงเหลือ</td>
      <td align="center" >LotNo</td>
      <td align="center" >ผป.ใหม่</td>
      <td align="center" >ชื่อ-สกุลผู้ป่วย</td>
      <td align="center" >เลขบัตรประจำตัวผู้ป่วย</td>
      <td align="center" >ประเภทบัตร</td>
      <td align="center" >ความหมายประเภทบัตร</td>
      <td align="center" >ต่างชาติ</td>
      <td align="center" >สถานะผู้ป่วยต่างชาติ</td>
    </tr>
    </thead>
    <tbody>
    <?php  do{ ?>
    <?php 
    $remain=$remain-$row_rs_result['sumqty'];
    if(strpos($row_rs_result['name1'].$row_rs_result['name2'].$row_rs_result['name3'].$row_rs_result['sname1'].$row_rs_result['sname2'].$row_rs_result['sname3'],"รายใหม่")){
        $new='Y';
    }else{
        $new='N';
    }
        ?>
    <tr>
      <td align="center" ><?php echo "10701"; ?></td>
      <td align="center" ><?php echo date_db2th2($row_rs_result['rxdate']); ?></td>
      <td align="center" ><?php echo ($row_rs_result['rxtime']); ?></td>      <td align="center" >21</td>
      <td align="center" ><?php echo $row_rs_config['hospitalcode']; ?></td>
      <td align="center" ><?php echo $row_rs_config['hospitalname']; ?></td>
      <td align="center" ><?php echo $row_rs_result['sumqty']; ?></td>
      <td align="center" ><?php echo $remain; ?></td>
      <td align="center" ></td>
      <td align="center" ><?php if($new=='Y'){ echo "1";}else{ echo "2";} ?></td>
      <td align="center" ><?php echo $row_rs_result['ptname']; ?></td>
      <td align="center" ><?php echo $row_rs_result['cid']; ?></td>
      <td align="center" >1</td>
      <td align="center" >บัตรประจำตัวประชาชน</td>
      <td align="center" >0</td>
      <td align="center" >คนไทย (สัญชาติไทย)</td>
    </tr>      
    <?php } while($row_rs_result = mysql_fetch_assoc($rs_result));?>    
    </tbody>
    </table>    
    </body>
</html>
<?php mysql_free_result($rs_config); ?>
<?php mysql_free_result($rs_result); ?>