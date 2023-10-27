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

if(isset($_POST['person1'])&&($_POST['person1']!="")){
	$condition.=" and c.couseller='".$_POST['person1']."'";
	$person1=$_POST['person1'];
	}		
if(isset($_GET['person1'])&&($_GET['person1']!="")){
	$condition.=" and c.couseller='".$_GET['person1']."'";
	$person1=$_GET['person1'];
	}		
if(isset($_POST['person2'])&&($_POST['person2']!="")){
	$condition.=" and c.recorder='".$_POST['person2']."'";
	$person2=$_POST['person2'];
	}		
if(isset($_GET['person2'])&&($_GET['person2']!="")){
	$condition.=" and c.recorder='".$_GET['person2']."'";
	$person2=$_GET['person2'];
	}		
if(isset($_POST['result'])&&($_POST['result']!="")){
	$condition.=" and c.result='".$_POST['result']."'";
	$result=$_POST['result'];
	}		
if(isset($_GET['result'])&&($_GET['result']!="")){
	$condition.=" and c.result='".$_GET['result']."'";
	$result=$_GET['result'];
	}		
if(isset($_POST['patient'])&&($_POST['patient']!="")){
	$condition.=" and c.patient='".$_POST['patient']."'";
	$patient=$_POST['patient'];
	}		
if(isset($_GET['patient'])&&($_GET['patient']!="")){
	$condition.=" and c.patient='".$_GET['patient']."'";
	$patient=$_GET['patient'];
	}		
if(isset($_POST['icode'])&&($_POST['icode']!="")){
	$condition.=" and c.icode='".$_POST['icode']."'";
	$icode=$_POST['icode'];
	}		
if(isset($_GET['icode'])&&($_GET['icode']!="")){
	$condition.=" and c.icode='".$_GET['icode']."'";
	$icode=$_GET['icode'];
	}		

if($_GET['hn']!=""){
    $condition.=" and c.hn=LPAD('".$_GET['hn']."',".$row_setting[24].",'0')";
}
include('include/function.php');
mysql_select_db($database_hos, $hos);
$query_rs_couselling = "select c.hn,c.record_date,c.record_time,c.patient_type,c.patient,c.other,c.result,c.problem,c.note,concat(s.name,' ',s.strength) as drugname,d.name as couseller,c.recorder,concat(p.pname,p.fname,'  ',p.lname) as patientname from ".$database_kohrx.".kohrx_couselling c  left outer join patient p on p.hn=c.hn left outer join s_drugitems s on s.icode=c.icode left outer join doctor d on d.code=c.couseller  where c.record_date between '".$date1."' and '".$date2."' ".$condition;
$rs_couselling = mysql_query($query_rs_couselling, $hos) or die(mysql_error());
$row_rs_couselling = mysql_fetch_assoc($rs_couselling);
$totalRows_rs_couselling = mysql_num_rows($rs_couselling);

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
<?php if($totalRows_rs_couselling<>0){ ?>
<table  id="example" class="table table-striped table-bordered table-hover table-sm " style="width:100%; font-size:14px" >  
	<thead>
  <tr >
    <td height="22" align="center">no.</td>
    <td align="center">วันที่</td>
    <td align="center">เวลา</td>
    <td align="center">HN</td>
    <td align="center">ชื่อ</td>
    <td align="center">รายการยา</td>
    <td align="center">ผู้ได้รับคำแนะนำ</td>
    <td align="center">ความเข้าใจ</td>
    <td align="center">ปัญหา</td>
    <td align="center">อื่นๆ</td>
    <td align="center">ผู้ให้คำปรึกษา</td>
    <td align="center">ผู้บันทึก</td>
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
      <td align="center" valign="top"><?php echo date_db2th($row_rs_couselling['record_date']); ?></td>
      <td align="center" valign="top"><?php echo $row_rs_couselling['record_time']; ?></td>
      <td align="center" valign="top"><?php echo "$row_rs_couselling[hn]"; ?></td>
      <td align="center" valign="top"><?php echo "$row_rs_couselling[patientname]"; ?></td>
      <td align="center" valign="top"><?php echo "$row_rs_couselling[drugname]"; ?></td>
      <td align="center" valign="top"><?php if($row_rs_couselling['patient']==1){ echo "ผู้ป่วย";} else { echo "$row_rs_couselling[other]"; }?></td>
      <td align="center" valign="top"><?php echo $result; ?></td>
      <td align="center" valign="top"><?php echo "$row_rs_couselling[problem]"; ?></td>
      <td align="center" valign="top"><?php echo "$row_rs_couselling[note]"; ?></td>
      <td align="center"><?php echo $row_rs_couselling['couseller']; ?></td>
      <td align="center"><?php echo doctorname($row_rs_couselling['recorder']);  ?></td>
      </tr> 
  <?php } while ($row_rs_couselling = mysql_fetch_assoc($rs_couselling)); ?>
		</tbody>
</table>
<?php } else{  echo nodata();  } ?>    

</body>
</html>
<?php
if($_GET['do']=="search"){

mysql_free_result($rs_couselling);
mysql_free_result($rs_setting);

}
?>
