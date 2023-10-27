<?php require_once('../Connections/hos.php'); ?>
<?php
$category1=$_GET['category1'];
$med_error_type1=$_GET['med_error_type1'];
$cause_id1=$_GET['cause_id1'];
$sub_id1=$_GET['sub_id1'];
$ptype1=$_GET['ptype1'];
$room_id=$_GET['room_id'];


if($category1!=""){
    $condition=" and r.category='".$category1."'";
}
if($med_error_type1!=""){
    $condition.=" and r.error_type='".$med_error_type1."'";
}
if($cause_id1!=""||$cause_id1!=NULL){
    $condition.=" and r.error_cause='".$cause_id1."'";
}
if($sub_id1!=""||$sub_id1!=NULL){
    $condition.=" and r.error_subtype='".$sub_id1."'";
}
if($ptype1!=""){
	$condition.=" and ptype='".$ptype1."'";
}
if($room_id!=""){
	$condition.=" and r.room_id='".$room_id."'";
}

include('../include/function.php');

mysql_select_db($database_hos, $hos);
$query_med_error_type = "SELECT r.*,d.name as dept_report_name,d2.name as dept_error_name,t.type_thai,t.id as tid,c.name as cause_name,s.sub_name
FROM ".$database_kohrx.".kohrx_med_error_report r 
left outer join hospital_department d on d.id=r.dep_report 
left outer join hospital_department d2 on d2.id=r.dep_error 
left outer join ".$database_kohrx.".kohrx_med_error_error_cause c on c.id=r.error_cause 
left outer join ".$database_kohrx.".kohrx_med_error_error_type t on t.id=r.error_type
left outer join ".$database_kohrx.".kohrx_med_error_error_sub_cause s on s.cause_id=r.error_subtype WHERE r.`date` between '".$_GET['date1']."' and '".$_GET['date2']."' ".$condition." ORDER BY r.`date`ASC";
//echo $query_med_error_type;
$med_error_type = mysql_query($query_med_error_type, $hos) or die(mysql_error());
$row_med_error_type = mysql_fetch_assoc($med_error_type);
$totalRows_med_error_type = mysql_num_rows($med_error_type);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายงานความคลาดเคลื่อนทางยา: <?php if($_GET['id']!=""){ echo $row_med_error_type['type_thai']; } ?> (<?php echo dateThai($date1); ?>-<?php echo dateThai($date2); ?>)</title>
<?php include('java_css_file.php'); ?> 

<!-- JS -->
<script src="https://cdn.datatables.net/buttons/1.6.0/js/dataTables.buttons.min.js" ></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.bootstrap4.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" ></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.html5.min.js" ></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.print.min.js" ></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.colVis.min.js" ></script>
<!-- CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.0/css/buttons.bootstrap4.min.css" >

<?php include('../include/bootstrap/datatable_report.php'); ?>

<style>
body {
	background-color: #FFFFFF;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
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
<?php if(!isset($_GET['export'])&&($_GET['export']!="Y")){ ?>
<?php } ?>
</head>

<body>
<nav class="navbar navbar-dark " style="background-color:#82b74b; color:#FFFFFF" >สรุปความคลาดเคลื่อนทางยาแยกตามประเภทความคลาดเคลื่อน<i class="fas fa-backspace navbar-right mr-4 cursor" style="font-size: 25px;" onClick="window.history.back(-1)"></i></nav>
<div style="background-color:#EBEBEB; padding: 10px;" class="thfont">
<p style="padding-left:10px; " ><strong>ประจำวันที่</strong> <?php echo dateThai($date1); ?> &nbsp;ถึง &nbsp;<?php echo dateThai($date2); ?><?php if($_GET['id']!=""){ ?><p style="padding-left:10px;" ><strong>ประเภทความคลาดเคลื่อน </strong>&nbsp;<?php echo $row_med_error_type['type_thai']; ?></p><?php } ?>
<?php if(!isset($_GET['export'])&&($_GET['export']!="Y")){ ?>
<!-- //ปิดปุ่ม export ไว้ก่อน

<a href="med_error_list_type.php?id=<?php echo $_GET['id']; ?>&amp;date1=<?php echo $_GET['date1']; ?>&amp;date2=<?php echo $_GET['date2']; ?>&amp;ptype=<?php echo $_GET['ptype']; ?>&amp;export=Y" class="thsan-light" style="text-decoration:none" target="_blank">
<div style="width:120px; color:#FFF; background-color:#006600; margin-bottom:10px; padding:5px" class="thsan-light font16" align="center"><img src="../images/excel.png" width="14" height="14" border="0" align="absmiddle" /> ส่งออก Excell
</div></a>
-->
<?php } ?>
</div>
<div style="padding:10px;overflow:scroll;overflow-x:auto;overflow-y:auto; height:75vh; padding: 10px;">
<table  id="example" class="table table-striped table-bordered table-hover table-sm display" style="width:100%; font-size:14px" >
  <thead>
  <tr class="thfont font12">
    <th  align="center" >ลำดับ</th>
    <th  align="center" >วดป. ที่รายงาน </th>
    <th  align="center" >เวลา</th>
    <th  align="center" >รายละเอียด</th>
    <th  align="center" >cat</th>
    <th align="center" >แผนกที่รายงาน</th>
    <th align="center" >ผู้รายงาน</th>
    <th align="center" >แผนกที่คลาดเคลื่อน</th>
    <th align="center" >ผู้คลาดเคลื่อน</th>
    <th align="center" >ประเภท</th>
    <th align="center" >ได้รับยา</th>
    <th align="center" >ประเภทความคลาดเคลื่อน</th>
    <th align="center" >รูปแบบความคลาดเคลื่อน</th>
    <th align="center" >รูปแบบย่อย</th>
    <th align="center" >คลาดเคลื่อนแบบอื่นๆ</th>
    <th align="center" >การแก้ไข</th>
    <th align="center" >ประเภทยา</th>
    <th align="center" >ยาที่เกี่ยวข้อง</th>
    <th align="center" >ผู้บันทึก</th>
    </tr>
  <tbody>
  <?php $i=1; do { ?>
  <?php 
	//ผู้รายงาน
	mysql_select_db($database_hos, $hos);
	$query_doctor = "select name,doctorcode from opduser where doctorcode='".$row_med_error_type['reporter']."'";
	$doctor = mysql_query($query_doctor, $hos) or die(mysql_error());
	$row_doctor = mysql_fetch_assoc($doctor);
	$totalRows_doctor = mysql_num_rows($doctor);
	$reporter=$row_doctor['name'];
  //ผู้เกิด error
	mysql_select_db($database_hos, $hos);
	$query_doctor = "select name,doctorcode from opduser where doctorcode='".$row_med_error_type['error_person']."'";
	$doctor = mysql_query($query_doctor, $hos) or die(mysql_error());
	$row_doctor = mysql_fetch_assoc($doctor);
	$totalRows_doctor = mysql_num_rows($doctor);
	$error_person=$row_doctor['name'];
  
  mysql_free_result($doctor);

	//ยาที่เกี่ยวข้อง
mysql_select_db($database_hos, $hos);
$query_drug = "select * from ".$database_kohrx.".kohrx_med_error_report_drug where rid='".$row_med_error_type['id']."'";
$drug = mysql_query($query_drug, $hos) or die(mysql_error());
$row_drug = mysql_fetch_assoc($drug);
$totalRows_drug = mysql_num_rows($drug);

	$drugs="";
	$d=0;
	do{
		$d++;
		mysql_select_db($database_hos, $hos);
		$query_drug2 = "select concat(name,' ',strength) as drug_name from drugitems where icode='".$row_drug['icode']."'";
		$drug2 = mysql_query($query_drug2, $hos) or die(mysql_error());
		$row_drug2 = mysql_fetch_assoc($drug2);
		$totalRows_drug2 = mysql_num_rows($drug2);
		if($d>1){
		$drugs.=" , ";	
		}
		$drugs.=$row_drug2['drug_name'];
		mysql_free_result($drug2);
				
	} while($row_drug = mysql_fetch_assoc($drug));
	mysql_free_result($drug)
  ?>
  <tr class="thfont font12">
    <td align="center"><? echo $i++; ?></td>
    <td align="center"><?php echo dateThai($row_med_error_type['date']); ?></td>
    <td align="center"><?php echo ($row_med_error_type['time']); ?></td>
    <td align="left"><?php print $row_med_error_type['detail']; ?></td>
    <td align="center"   ><strong><?php print $row_med_error_type['category']; ?></strong></td>
    <td align="center" ><?php print $row_med_error_type['dept_report_name']; ?></td>
    <td align="center" ><?php echo $reporter; ?></td>
    <td align="center" ><?php print $row_med_error_type['dept_error_name']; ?></td>
    <td align="center" ><?php echo $error_person; ?></td>
    <td align="center" ><?php print $row_med_error_type['ptype']; ?></td>
    <td align="center" ><?php print $row_med_error_type['reciew']; ?></td>
    <td align="left" ><?php print $row_med_error_type['type_thai']; ?></td>
    <td align="left" ><?php print $row_med_error_type['cause_name']; ?></td>
    <td align="left" ><?php print $row_med_error_type['sub_name']; ?></td>
    <td align="left" ><?php print $row_med_error_type['note']; ?></td>
    <td align="left" ><?php print $row_med_error_type['suggest']; ?></td>
    <td align="center" ><?php if($row_med_error_type['drugtype']=="NM"){ print "ยาทั่วไป"; } else if($row_med_error_type['drugtype']=="HAD"){ print "ยาความเสี่ยงสูง"; } ?></td>
    <td align="center" ><?php echo $drugs; ?></td>
    <td align="center" ><?php print $row_med_error_type['pharmacist']; ?></td>
    </tr>
  <?php } while ($row_med_error_type = mysql_fetch_assoc($med_error_type)); ?>
  </tbody>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($med_error_type);
?>
