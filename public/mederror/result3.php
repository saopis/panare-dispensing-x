<?php require_once('../Connections/hos.php'); ?>
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
include('../include/function.php'); 
mysql_select_db($database_hos, $hos);
$query_rs_config = "SELECT (select d.`name` FROM ".$database_kohrx.".kohrx_dispensing_setting s left outer join hospital_department d on d.id=s.`value` WHERE s.id=1) as dept_name,(select hospitalname from opdconfig) as hospname";
$rs_config = mysql_query($query_rs_config, $hos) or die(mysql_error());
$row_rs_config = mysql_fetch_assoc($rs_config);
$totalRows_rs_config = mysql_num_rows($rs_config);

mysql_select_db($database_hos, $hos);
$query_rs_report = "select r.*,tt.type_thai,c.`name`,sc.sub_name,o.note,h1.name as dep_report,h2.name as dep_error from ".$database_kohrx.".kohrx_med_error_report r 
left outer join ".$database_kohrx.".kohrx_med_error_cause mc on mc.rid=r.id 
left outer join ".$database_kohrx.".kohrx_med_error_error_cause c on c.id=mc.cause_id 
left outer join ".$database_kohrx.".kohrx_med_error_error_type tt on tt.id=c.type_id
left outer join ".$database_kohrx.".kohrx_med_error_subtype st on st.med_id=r.id 
left outer join ".$database_kohrx.".kohrx_med_error_error_sub_cause sc on sc.id=st.sub_id
left outer join ".$database_kohrx.".kohrx_med_error_other_note o on o.med_id=r.id
left outer join hospital_department h1 on h1.id=r.dep_report
left outer join hospital_department h2 on h2.id=r.dep_error

 where r.id='".$_GET['report_id']."'";
$rs_report = mysql_query($query_rs_report, $hos) or die(mysql_error());
$row_rs_report = mysql_fetch_assoc($rs_report);
$totalRows_rs_report = mysql_num_rows($rs_report);

mysql_select_db($database_hos, $hos);
$query_rs_reporter = "select name from doctor where code='".$row_rs_report['reporter']."'";
$rs_reporter = mysql_query($query_rs_reporter, $hos) or die(mysql_error());
$row_rs_reporter = mysql_fetch_assoc($rs_reporter);
$totalRows_rs_reporter = mysql_num_rows($rs_reporter);

mysql_select_db($database_hos, $hos);
$query_rs_reporter1 = "select name from doctor where code='".$row_rs_report['error_person']."'";
$rs_reporter1 = mysql_query($query_rs_reporter1, $hos) or die(mysql_error());
$row_rs_reporter1 = mysql_fetch_assoc($rs_reporter1);
$totalRows_rs_reporter1 = mysql_num_rows($rs_reporter1);

mysql_select_db($database_hos, $hos);
$rs_drug = "select d.*,o.drug_option_name from ".$database_kohrx.".kohrx_med_error_report_drug d left outer join ".$database_kohrx.".kohrx_med_error_drug_option o on o.id=d.drug_option where d.rid='".$_GET['report_id']."'";
$qdrug = mysql_query($rs_drug,$hos)  or die (mysql_error());
$row_drug = mysql_fetch_assoc($qdrug);
$totalRows = mysql_num_rows($qdrug);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body onload="window.print();">
<div style="border: 1px #000000 solid; background-color: #CCC" >
<div class="thfont" style=" font-size:20px; font-weight:bold; padding:5px;" align="center">รายงานความคลาดเคลื่อนทางยา</div>
<div class="thfont" style="margin-top:0px;" align="center"><?php echo $row_rs_config['dept_name']; ?>&nbsp;&nbsp;โรงพยาบาล<?php echo $row_rs_config['hospname']; ?></div>
</div>
<div><table width="100%" border="0" cellspacing="0" cellpadding="3" class="thfont" style="border-collapse:collapse">
  <tr>
    <td style="border:1px solid #000; border-top:0px; "><strong>รายงานหมายเลข :</strong> <?php echo $row_rs_report['id']; ?></td>
    <td style="border:1px solid #000; border-top:0px; "><strong>วันและเวลาที่เกิดความคลาดเคลื่อน : </strong><?php echo dateThai($row_rs_report['date']); ?> <?php echo $row_rs_report['time']; ?></td>
  </tr>
  <tr>
    <td style="border:1px solid #000;"><strong>ผู้รายงาน :</strong> <?php echo $row_rs_reporter['name']; ?></td>
    <td style="border:1px solid #000;"><strong>ผู้ที่เกิดความคลาดเคลื่อน :</strong> <?php echo $row_rs_reporter1['name']; ?></td>
  </tr>
  <tr>
    <td style="border:1px solid #000;"><strong>หน่วยงานที่รายงาน : </strong><?php echo $row_rs_report['dep_report']; ?></td>
    <td style="border:1px solid #000;"><strong>หน่วยงานที่เกิดความคลาดเคลื่อน : </strong><?php echo $row_rs_report['dep_error']; ?></td>
  </tr>
  <tr>
    <td style="border:1px solid #000;"><strong>HN/AN :</strong> <?php echo $row_rs_report['hn']; ?></td>
    <td style="border:1px solid #000;"><strong>ประเภทผู้ป่วย :</strong> <?php echo $row_rs_report['ptype']; ?></td>
  </tr>
  <tr>
    <td colspan="2" style="border:1px solid #000;"><strong>ประเภทความคลาดเคลื่อน :</strong>
      <?php if($row_rs_report['note']==""){ echo $row_rs_report['type_thai']." &gt;&gt; ".$row_rs_report['name']; if($row_rs_report['sub_name']!=""){ echo " &gt;&gt; ".$row_rs_report['sub_name'];} } else { echo $row_rs_report['note']; } ?><br />
      <strong>การได้รับยา :</strong> <?php echo $row_rs_report['reciew']; ?>&nbsp;&nbsp;<strong>ระดับความรุนแรง(Risk Category) :</strong> <?php echo $row_rs_report['category']; ?></td>
    </tr>
 </table>
 <table width="100%" border="0" cellspacing="0" cellpadding="3" class="thfont" style="border-collapse:collapse; margin-top:10px;">  
  <tr>
    <td></td>
    </tr>
  <tr>
    <td style="font-size:16px; font-weight:bold; border:solid 1px #000000; background-color:#CCCCCC">เหตุการความคลาดเคลื่อน</td>
    </tr>
  <tr>
    <td style="padding-bottom:20px; border:solid 1px #000000;"><?php echo $row_rs_report['detail']; ?></td>
    </tr>
  <tr>
    <td style="font-size:16px; font-weight:bold; border:solid 1px #000000; background-color:#CCCCCC">แนวทางการแก้ไข</td>
    </tr>
  <tr>
    <td style="padding-bottom:20px; border:solid 1px #000000;"><?php echo $row_rs_report['suggest']; ?></td>
  </tr>
  <tr>
    <td style="font-size:16px; font-weight:bold; border:solid 1px #000000; background-color:#CCCCCC">ยาที่เกี่ยวข้อง</td>
    </tr>
    <?php if($totalRows<>0){ ?>
	<?php $i=0; do{ $i++; 
	mysql_select_db($database_hos, $hos);
	$rs_drug1 = "select concat(name,strength) as drugname from drugitems where icode='".$row_drug['icode']."'";
	$qdrug1 = mysql_query($rs_drug1,$hos)  or die (mysql_error());
	$row_drug1 = mysql_fetch_assoc($qdrug1);	
	?>
  <tr>
    <td style="padding-bottom:20px; border:solid 1px #000000;"><?php echo $i.". ".$row_drug1['drugname']."( ".$row_drug['drug_option_name']." )"; ?></td>
    </tr>
   <?php
   mysql_free_result($qdrug1);

    }while($row_drug = mysql_fetch_assoc($qdrug)); } ?>
  <tr>
    <td style="padding-top:20px;"><strong>ผู้บันทึก :</strong> <?php echo $row_rs_report['pharmacist']; ?></td>
    </tr>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($rs_config);

mysql_free_result($rs_report);

mysql_free_result($rs_reporter);

mysql_free_result($rs_reporter1);

mysql_free_result($qdrug);

?>
