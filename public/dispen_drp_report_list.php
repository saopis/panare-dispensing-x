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

if(isset($_GET['drp_code'])&&($_GET['drp_code']!="")){
	$condition.=" and c.std_code='".$_GET['drp_code']."'";
	}		
if(isset($_GET['icode'])&&($_GET['icode']!="")){
	$condition.=" and l.icode='".$_GET['icode']."'";
	}		
if(isset($_GET['intervention1'])&&($_GET['intervention1']!="")){
	$condition.=" and drp_intervention_type_id_1='".$_GET['intervention1']."'";
	}		
if(isset($_GET['intervention2'])&&($_GET['intervention2']!="")){
	$condition.=" and drp_intervention_type_id_2='".$_GET['intervention2']."'";
	}		
if(isset($_GET['intervention3'])&&($_GET['intervention3']!="")){
	$condition.=" and drp_intervention_type_id_3='".$_GET['intervention3']."'";
	}		
if(isset($_GET['outcome'])&&($_GET['outcome']!="")){
	$condition.=" and l.drp_outcome_type_id='".$_GET['outcome']."'";
	}		
if(isset($_GET['follow_up'])){ 
	if($_GET['follow_up']!=""){
	$condition.=" and need_follow_up='".$_GET['follow_up']."'";
	}		
}
if(isset($_GET['remark'])&&($_GET['remark']!="")){
	$condition.=" and l.intervention_note like '%".$_GET['remark']."%'";
	}		
include('include/function.php');
include('include/function_sql.php');
mysql_select_db($database_hos, $hos);
$query_rs_couselling = "select v.vstdate,r.hn,icode,l.drp_cause_id,drp_intervention_type_id_1,drp_intervention_type_id_2,drp_intervention_type_id_3,l.drp_outcome_type_id,need_follow_up,intervention_note from drp_problem_list l left outer join drp_regist r on r.drp_regist_id=l.drp_regist_id left outer join drp_cause c on c.drp_cause_id=l.drp_cause_id left outer join vn_stat v on v.vn=r.vn where (v.vstdate between '".$date1."' and '".$date2."') ".$condition;
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
    <td width="19%" align="center">รายการยา</td>
    <td width="8%" align="center">รหัสปัญหา</td>
    <td width="5%" align="center">inv.1</td>
    <td width="11%" align="center">inv.2</td>
    <td width="10%" align="center">inv.3</td>
    <td width="11%" align="center">ผลลัพธ์</td>
    <td width="11%" align="center">หมายเหตุ</td>
    <td width="11%" align="center">ติดตาม</td>
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
      <td align="center" valign="top" data-toggle="tooltip" data-placement="bottom" title="<?php echo drp_cause_name($row_rs_couselling['drp_cause_id']); ?>"><?php echo drp_cause($row_rs_couselling['drp_cause_id']); ?></td>
      <td align="center" valign="top" data-toggle="tooltip" data-placement="bottom" title="<?php echo interventionname($row_rs_couselling['drp_intervention_type_id_1']); ?>"><?php echo $row_rs_couselling['drp_intervention_type_id_1']; ?></td>
      <td align="center" valign="top" data-toggle="tooltip" data-placement="bottom" title="<?php echo interventionname($row_rs_couselling['drp_intervention_type_id_2']); ?>"><?php echo $row_rs_couselling['drp_intervention_type_id_2']; ?></td>
      <td align="center" valign="top" data-toggle="tooltip" data-placement="bottom" title="<?php echo interventionname($row_rs_couselling['drp_intervention_type_id_3']); ?>"><?php echo $row_rs_couselling['drp_intervention_type_id_3']; ?></td>
      <td align="center"><?php echo outcomename($row_rs_couselling['drp_outcome_type_id']); ?></td>
      <td align="center"><?php print $row_rs_couselling['intervention_note']; ?></td>
      <td align="center"><?php print $row_rs_couselling['need_follow_up']; ?></td>
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
