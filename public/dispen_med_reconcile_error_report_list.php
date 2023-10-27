<?php require_once('Connections/hos.php'); ?>
<?php
echo $_GET['error_subtype'];
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
mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));


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
if(isset($_GET['icode'])&&($_GET['icode']!="")){
	$condition.=" and m.icode in (".$_GET['icode'].")";
	$icode=$_GET['icode'];

}	
if(isset($_GET['error_type'])&&($_GET['error_type']!="")){
	$condition.=" and e.error_type='".$_GET['error_type']."'";
	$error_type=$_GET['error_type'];
	}		
if(isset($_GET['error_cause'])&&($_GET['error_cause']!="")){
	$condition.=" and e.error_cause='".$_GET['error_cause']."'";
	$error_cause=$_GET['error_cause'];
	}		
if(isset($_GET['error_subtype'])&&($_GET['error_subtype']!=""||$_GET['error_subtype']!=NULL)){
	$condition.=" and e.error_subtype='".$_GET['error_subtype']."'";
	$error_subtype=$_GET['error_subtype'];
	}		
		
if(isset($_GET['consult'])&&($_GET['consult']!="")){
	$condition.=" and e.consult='".$_GET['consult']."'";
	$consult=$_GET['consult'];
	}		

if($_GET['hn']!=""){
    $condition.=" and c.hn=LPAD('".$_GET['hn']."',".$row_setting[24].",'0')";
}
if(isset($_GET['reporter'])&&($_GET['reporter']!="")){
    $condition.=" and e.reporter='".$_GET['reporter']."'";
	$reporter=$_GET['reporter'];
}

include('include/function.php');
mysql_select_db($database_hos, $hos);
$query_rs_error = "select m.*,e.error_type,e.error_cause,e.error_subtype,e.category,e.consult,e.detail,e.solv,e.reporter,e.drug_type,concat(p.pname,p.fname,'  ',p.lname) as ptname,d.name from ".$database_kohrx.".kohrx_med_reconcile_error e left outer join ".$database_kohrx.".kohrx_med_reconcile m on m.id=e.med_reconcile_id left outer join patient p on p.hn=m.hn left outer join doctor d on d.code=e.reporter  where m.vstdate2 between '".$date1."' and '".$date2."' ".$condition;
//echo $query_rs_error;
$rs_error = mysql_query($query_rs_error, $hos) or die(mysql_error());
$row_rs_error = mysql_fetch_assoc($rs_error);
$totalRows_rs_error = mysql_num_rows($rs_error);

include('include/function_sql.php');

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
<?php if($totalRows_rs_error<>0){ ?>
<table  id="example" class="table table-striped table-bordered table-hover table-sm " style="width:100%; font-size:14px" >  
	<thead>
  <tr >
    <td height="22" align="center">no.</td>
    <td align="center">วันที่</td>
    <td align="center">HN</td>
    <td align="center">ชื่อ</td>
    <td align="center">รายการยา</td>
    <td align="center">ประเภท error</td>
    <td align="center">cat.</td>
    <td align="center">การขอคำปรึกษา</td>
    <td align="center">ประเภทยา</td>
    <td align="center">รายละเอียด/แก้ไข</td>
    <td align="center">ผู้บันทึก</td>
  </tr>
	</thead>
	<tbody>
   <?php $i=0; do { $i++; 
   switch ($row_rs_error['result']){
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
      <td align="center" valign="top"><?php echo date_db2th($row_rs_error['vstdate2']); ?></td>
      <td align="center" valign="top"><?php echo $row_rs_error['hn']; ?></td>
      <td align="center" valign="top"><?php echo $row_rs_error['ptname']; ?></td>
      <td align="center" valign="top"><?php echo $row_rs_error['drug_name']; ?></td>
      <td align="left" valign="top"><div class="font_border"><?php echo name_error_type($row_rs_error['error_type']); ?></div><div class="pl-3"><?php echo "-".name_error_cause($row_rs_error['error_cause']); ?></div><?php if($row_rs_error['error_subtype']<>0){ ?><div class="pl-5"><?php echo "-".name_error_subtype($row_rs_error['error_subtype']); ?></div><?php } ?></td>
      <td align="center"><?php echo $row_rs_error['category']; ?></td>
      <td align="center"><?php if($row_rs_error['consult']=="0"){ echo "ไม่ได้ consult"; } else if($row_rs_error['consult']=="1"){ echo "consult/ไม่เปลี่ยน"; } else if($row_rs_error['consult']=="2"){ echo "consult/เปลี่ยน"; } ?></td>
      <td align="center"><?php if($row_rs_error['drug_type']=="1"){ echo "Admit"; } else if($row_rs_error['drug_type']=="2"){ echo "Discharge"; } ?></td>
      <td align="left"><div><strong>รายละเอียด : </strong><?php echo $row_rs_error['detail']; ?></div><?php if($row_rs_error['solv']!=""){ ?><div><strong>การแก้ไข : </strong><?php echo $row_rs_error['solv']; ?></div><?php } ?></td>      
        <td align="center"><?php echo doctorname($row_rs_error['reporter']);  ?></td>
      </tr> 
  <?php } while ($row_rs_error = mysql_fetch_assoc($rs_error)); ?>
		</tbody>
</table>
<?php } else{  echo nodata();  } ?>    

</body>
</html>
<?php
if($_GET['do']=="search"){

mysql_free_result($rs_error);
mysql_free_result($rs_setting);

}
?>
