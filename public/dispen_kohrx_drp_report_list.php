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
function utf8_strlen($str)
   {
      $c = strlen($str);
            $l = 0;
      for ($i = 0; $i < $c; ++$i)
      {
         if ((ord($str[$i]) & 0xC0) != 0x80)
         {
            ++$l;
         }
      }
      return $l;
   }


mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

mysql_free_result($rs_setting);


if(isset($_POST['do'])&&($_POST['do']!="")){
$do=$_POST['do'];
}
if(isset($_GET['do'])&&($_GET['do']!="")){
$do=$_GET['do'];
}

if($do=="search"){

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
if($_GET['hn']!=""){
	$condition.=" and r.hn =LPAD('".$_GET['hn']."','".$row_setting[24]."','0')";
	}

if(isset($_GET['drug'])&&($_GET['drug']!="")){
	$condition.=" and d.icode='".$_GET['drug']."'";
	}		
if(isset($_GET['cat'])&&($_GET['cat']!="")){
	$condition.=" and r.drp_cat='".$_GET['cat']."'";
	}		
if(isset($_GET['pttype'])&&($_GET['pttype']!="")){
	$condition.=" and r.pttype='".$_GET['pttype']."'";
	}		
if(isset($_GET['problem'])&&($_GET['problem']!="")){
	$condition.=" and  locate('".$_GET['problem']."',r.problem ) ";
	}		


include('include/function.php');
include('include/function_sql.php');
mysql_select_db($database_hos, $hos);
$query_rs_couselling = "select r.*,c.name_thai from ".$database_kohrx.".kohrx_drp_record r left outer join ".$database_kohrx.".kohrx_drp_drug d on d.drp_id=r.id left outer join ".$database_kohrx.".kohrx_drp_category c on c.drp_cat=r.drp_cat where (r.record_date between '".$date1."' and '".$date2."') ".$condition." group by r.id";
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
    <td width="8%" align="center">ประเภท DRP</td>
    <td width="8%" align="center">ประเด็นปัญหา</td>
    <td width="8%" align="center">รายละเอียด</td>
    <td width="8%" align="center">การแก้ไข</td>
    <td width="8%" align="center">ผลลัพธ์</td>
    <td width="8%" align="center">รายการยาที่เกี่ยวข้อง</td>
    <td width="8%" align="center">ผู้บันทึก</td>
    </tr>
	</thead>
	<tbody>
   <?php $i=0; do { $i++; 
	mysql_select_db($database_hos, $hos);
	$query_rs_drug = "select concat(d.name,' ',d.strength) as drugname from ".$database_kohrx.".kohrx_drp_drug k left outer join drugitems d on d.icode=k.icode where k.drp_id='".$row_rs_couselling['id']."' ";
	$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
	$row_rs_drug = mysql_fetch_assoc($rs_drug);
	$totalRows_rs_drug = mysql_num_rows($rs_drug);				   
				   
   ?><tr  >
      <td align="center" valign="top"><?php echo $i; ?></td>
      <td align="center" valign="top"><?php echo dateThai3($row_rs_couselling['record_date']); ?></td>
      <td align="center" valign="top"><?php echo "$row_rs_couselling[hn]"; ?></td>
      <td align="center" valign="top"><nobr><?php echo ptname($row_rs_couselling['hn']); ?></nobr></td>
      <td align="center" valign="top" ><?php echo $row_rs_couselling['name_thai']; ?></td>
      <td align="left" valign="top" ><?php echo $row_rs_couselling['title'];  ?></td>
      <td align="left" valign="top" ><?php echo $row_rs_couselling['detail']; ?></td>
      <td align="left" valign="top" ><?php echo $row_rs_couselling['solv']; ?></td>
      <td align="left" valign="top" ><?php echo $row_rs_couselling['result']; ?></td>
      <td align="left" valign="top" >
		<?php if($totalRows_rs_drug<>0){ $d=0; do{ $d++; ?>
		  <div class="p-1" style="font-size: 12px;"><nobr><?php echo $d.". ".$row_rs_drug['drugname']; ?></nobr></div>
		 <?php }while($row_rs_drug = mysql_fetch_assoc($rs_drug)); } mysql_free_result($rs_drug); ?>
		</td>
      <td align="center" valign="top" ><?php echo doctorname($row_rs_couselling['recorder']); ?></td>
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
mysql_free_result($rs_couselling);
?>
