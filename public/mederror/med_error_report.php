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
$query_report = "SELECT r.id, r.detail,doc.name as docname,r.`date`, r.dep_report, r.category,d.name,tt.type_thai,tt.id as tid FROM ".$database_kohrx.".kohrx_med_error_report as r  left outer join hospital_department d on d.id=r.dep_report left outer join ".$database_kohrx.".kohrx_med_error_error_cause c on c.id=r.error_cause left outer join ".$database_kohrx.".kohrx_med_error_error_type tt on tt.id=r.error_type left outer join doctor doc on doc.code=r.error_person WHERE r.id!='' ".$condition." and r.`date`between '".$_GET['date1']."' and '".$_GET['date2']."' ORDER BY r.`date`ASC";
//echo $query_report;
$report = mysql_query($query_report, $hos) or die(mysql_error());
$row_report = mysql_fetch_assoc($report);
$totalRows_report = mysql_num_rows($report);

mysql_select_db($database_hos, $hos);
$query_patient = "select count(ov.hn) as chn  from vn_stat ov, ovst ovst, patient pt  where  ov.vn=ovst.vn and pt.hn=ov.hn and ov.vstdate between '".$_GET['date1']."' and  '".$_GET['date2']."'  and ov.age_y>= 0   and ov.age_y<= 200 ";
$patient = mysql_query($query_patient, $hos) or die(mysql_error());
$row_patient = mysql_fetch_assoc($patient);
$totalRows_patient = mysql_num_rows($patient);

mysql_select_db($database_hos, $hos);
$query_ipd1 = "select sum(admdate) as cc from an_stat where dchdate between '".$_GET['date1']."' and '".$_GET['date2']."'";
$ipd1 = mysql_query($query_ipd1, $hos) or die(mysql_error());
$row_ipd1 = mysql_fetch_assoc($ipd1);
$totalRows_ipd1 = mysql_num_rows($ipd1);

if($totalRows_report<>0){
mysql_select_db($database_hos, $hos);
$query_error_type = "SELECT tt.id,tt.type_thai,count(r.id) countid FROM ".$database_kohrx.".kohrx_med_error_report as r left outer join hospital_department d on d.id=r.dep_report left outer join ".$database_kohrx.".kohrx_med_error_error_cause c on c.id=r.error_cause left outer join ".$database_kohrx.".kohrx_med_error_error_type tt on tt.id=r.error_type WHERE r.id!='' ".$condition." and r.`date`between '".$_GET['date1']."' and '".$_GET['date2']."'  GROUP BY tt.id ORDER BY r.`date`ASC";
$error_type = mysql_query($query_error_type, $hos) or die(mysql_error());
$row_error_type = mysql_fetch_assoc($error_type);
$totalRows_error_type = mysql_num_rows($error_type);

mysql_select_db($database_hos, $hos);
$query_category = "SELECT r.category,count(r.id) FROM ".$database_kohrx.".kohrx_med_error_report as r left outer join ".$database_kohrx.".kohrx_med_error_error_cause c on c.id=r.error_cause left outer join ".$database_kohrx.".kohrx_med_error_error_type tt on tt.id=r.error_type WHERE 1=1 ".$condition." and r.`date`between '".$_GET['date1']."' and '".$_GET['date2']."' group by r.category ORDER BY r.category ASC";
//echo 3;
$category = mysql_query($query_category, $hos) or die(mysql_error());
$row_category = mysql_fetch_assoc($category);
$totalRows_category = mysql_num_rows($category);

mysql_select_db($database_hos, $hos);
$query_dep_report = "SELECT d.name ,count(d.name) as countid FROM ".$database_kohrx.".kohrx_med_error_report as r left outer join hospital_department d on d.id=r.dep_report left outer join ".$database_kohrx.".kohrx_med_error_error_cause c on c.id=r.error_cause left outer join ".$database_kohrx.".kohrx_med_error_error_type tt on tt.id=r.error_type WHERE r.date between '".$_GET['date1']."' and '".$_GET['date2']."' ".$condition."  GROUP BY r.dep_report";
//echo 4;
$dep_report = mysql_query($query_dep_report, $hos) or die(mysql_error());
$row_dep_report = mysql_fetch_assoc($dep_report);
$totalRows_dep_report = mysql_num_rows($dep_report);

mysql_select_db($database_hos, $hos);
$query_dep_error = "SELECT d.name ,count(d.id) as countid FROM ".$database_kohrx.".kohrx_med_error_report as r left outer join ".$database_kohrx.".kohrx_med_error_error_cause c on c.id=r.error_cause left outer join ".$database_kohrx.".kohrx_med_error_error_type tt on tt.id=r.error_type left outer join hospital_department d on d.id=r.dep_error WHERE r.date between '".$_GET['date1']."' and '".$_GET['date2']."' and r.dep_error !='' ".$condition." GROUP BY r.dep_error";
//echo $query_dep_error;
//echo 5;
$dep_error = mysql_query($query_dep_error, $hos) or die(mysql_error());
$row_dep_error = mysql_fetch_assoc($dep_error);
$totalRows_dep_error = mysql_num_rows($dep_error);

mysql_select_db($database_hos, $hos);
$query_med_error_type = "SELECT tt.type_thai ,count(tt.id),tt.id FROM  ".$database_kohrx.".kohrx_med_error_report as r left outer join ".$database_kohrx.".kohrx_med_error_error_cause ec on ec.id=r.error_cause left outer join ".$database_kohrx.".kohrx_med_error_error_type tt on tt.id=r.error_type WHERE  r.date between '".$_GET['date1']."' and '".$_GET['date2']."' and r.error_type!='' ".$condition." GROUP BY tt.id";
//echo $query_med_error_type;
//echo 6;
$med_error_type = mysql_query($query_med_error_type, $hos) or die(mysql_error());
$row_med_error_type = mysql_fetch_assoc($med_error_type);
$totalRows_med_error_type = mysql_num_rows($med_error_type);

mysql_select_db($database_hos, $hos);
$query_error_person = "SELECT d.name ,count(d.code) as countid FROM ".$database_kohrx.".kohrx_med_error_report as r  left outer join doctor d on d.code=r.error_person WHERE r.date between '".$_GET['date1']."' and '".$_GET['date2']."' ".$condition." and d.name!='' GROUP BY r.error_person ORDER BY count(d.code) DESC, d.name ASC";
//echo 7;
$error_person = mysql_query($query_error_person, $hos) or die(mysql_error());
$row_error_person = mysql_fetch_assoc($error_person);
$totalRows_error_person = mysql_num_rows($error_person);
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?> 
<style type="text/css">
<!--
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
</head>

<body>
<nav class="navbar navbar-dark bg-success text-white ">
  <!-- Navbar content -->
    <span class="font18"><i class="fas fa-asterisk font20"></i>&ensp;จำแนกรายละเอียดความคลาดเคลื่อนทางยา</span>
</nav>
<div style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:90vh; padding: 10px;">
<div class="card">
<div class="card-body thfont">
จำนวนรายงานทั้งหมด <span class="text-danger font16"><?php echo $totalRows_report; ?></span> รายงาน <?php if ($totalRows_patient > 0) { ?>จำนวนผู้ป่วย (visit) <span class="text-danger font16"><?php echo $row_patient['chn']; ?> </span> <?php }  ?>&nbsp;คน<?php if ($totalRows_ipd1 > 0) {  ?>&nbsp;จำนวนวันนอน&nbsp;<?php echo $ipd_count=number_format($row_ipd1['cc']); ?> &nbsp;วัน<?php }  ?>

</div>
</div>
<div class="row mt-2">
	<div class="col-sm-6">
		<?php if($totalRows_category<>0){ ?>
        <div class="card">
			<div class="card-header "><strong>สรุปแบบแยกระดับความรุนแรง</strong></div>
			<div class="card-body p-0">
            <table class="table table-hover thfont font14">
			<thead class="thead-dark">
                <tr class="text-white bg-dark text-center">
				<th >category</th>
				<th >จำนวนรายงาน</th>
				<th >%</th>
				</tr>
			</thead>
			<tbody>
              <?php do { ?>
				<tr>
				<td align="center"><?php echo $row_category['category']; ?></td>
				<td align="center"><?php echo $row_category['count(r.id)']; ?></td>
				<td align="center"><? $percent=($row_category['count(r.id)']/$totalRows_report)*100;  echo number_format($percent,2); ?>&nbsp;%</td>
				</tr>
              <?php } while ($row_category = mysql_fetch_assoc($category)); ?>
			</tbody>
			</table>
			</div>
		</div>
		<?php } if($totalRows_dep_report<>0){ ?>
        <div class="card mt-2">
			<div class="card-header "><strong>
              จำนวนการรายงานความคลาดเคลื่อนทางยาของแต่ละฝ่าย</strong></div>
			<div class="card-body p-0">
              <table class="table thfont font14">
				  <thead class="thead-dark">
                <tr class="text-white bg-dark">
                  <td  align="center"  >ลำดับ</td>
                  <td  align="center" >หน่วยงานที่รายงาน</td>
                  <td  align="center" >จำนวนรายงาน</td>
                  <td  align="center" >%</td>
                </tr>
				</thead>
				 <tbody>
                <?php do { ?>
                <?php  $i=1;  ?>
                <tr>
                  <td align="center" bgcolor="#FFFFFF" class="normal1">&nbsp;<? echo $i++; ?></td>
                  <td align="center" bgcolor="#FFFFFF" class="normal"><?php echo  $row_dep_report['name']; ?></td>
                  <td align="center" bgcolor="#FFFFFF" class="normal"><?php echo $row_dep_report['countid']; ?></td>
                  <td align="center" bgcolor="#FFFFFF" class="normal"><? $percent=($row_dep_report['countid']/$totalRows_report)*100;  echo number_format($percent,2); ?></td>
                </tr>
                <?php } while ($row_dep_report = mysql_fetch_assoc($dep_report)); ?>
			</tbody>
              </table>
			</div>
		</div>
		<?php } if($totalRows_dep_error<>0){ ?>
        <div class="card mt-2">
			<div class="card-header "><strong>จำนวนรายงานความคลาดเคลื่อนทางยาที่เกิดขึ้นแต่ละฝ่าย </strong></div>
			<div class="card-body p-0">
              <table  class="table thfont font14">
				 <thead class="thead-dark">
                <tr class="text-white bg-dark">
                  <td  align="center" >ลำดับ</td>
                  <td  align="center">หน่วยงานเกิดความคลาดเคลื่อน</td>
                  <td  align="center">จำนวนรายงาน</td>
                  <td  align="center" >%</td>
                </tr>
				  </thead>
				  <tbody>
                <?php $i=0; do { ?>
                <?php  $i++;  ?>
                <tr>
                  <td align="center" bgcolor="#FFFFFF" class="normal1">&nbsp;<? echo $i++; ?></td>
                  <td align="left" bgcolor="#FFFFFF" class="normal"><?php echo $row_dep_error['name']; ?></td>
                  <td align="center" bgcolor="#FFFFFF" class="normal"><?php echo $row_dep_error['countid']; ?></td>
                  <td align="center" bgcolor="#FFFFFF" class="normal"><? $percent=($row_dep_error['countid']/$totalRows_report)*100;  echo number_format($percent,2); ?></td>
                </tr>
                <?php } while ($row_dep_error = mysql_fetch_assoc($dep_error)); ?>
				</tbody>
              </table>

			</div>
		</div>
		<?php } if($totalRows_error_person<>0){ ?>
        <div class="card mt-2">
			<div class="card-header "><strong>รายงานแยกตามบุคคลที่เกิดความคลาดเคลื่อน</strong></div>
			<div class="card-body p-0">
              <table class="table thfont font14">
				<thead class="thead-dark">
                <tr class="text-white bg-dark text-center">
                  <th  align="center" >ลำดับ</th>
                  <th  align="center" >เจ้าหน้าที่</th>
                  <th  align="center" >จำนวนรายงาน</th>
                  <th  align="center" >%</th>
                </tr>
			    </thead>
				<tbody>
                <?php $i=0; do { $i++; ?>
                <tr>
                  <td align="center" bgcolor="#FFFFFF"><? echo $i; ?></td>
                  <td align="center" bgcolor="#FFFFFF"><?php echo $row_error_person['name']; ?></td>
                  <td align="center" bgcolor="#FFFFFF"><?php echo $row_error_person['countid']; ?></td>
                  <td align="center" bgcolor="#FFFFFF"><? $percent=($row_error_person['countid']/$totalRows_report)*100;  echo number_format($percent,2); ?></td>
                </tr>
                <?php } while ($row_error_person = mysql_fetch_assoc($error_person)); ?>
				</tbody>
            </table>				
			</div>
		</div>
		<?php } ?>
		
	</div>
	<div class="col-sm-6">
		<?php if($totalRows_error_type<>0){ ?>
        <div class="card">
			<div class="card-header "><strong>รายงานจำแนกตามประเภทความคลาดเคลื่อนทางยา </strong><button type="button" class="btn btn-secondary btn-sm"  onClick="window.location='med_error_list_type.php?date1=<?php echo $_GET['date1']; ?>&date2=<?php echo $_GET['date2']; ?>&category1=<?php echo $category1; ?>&med_error_type1=<?php echo $med_error_type1; ?>&cause_id1=<?php echo $cause_id1; ?>&sub_id1=<?php echo $sub_id1; ?>&ptype1=<?php echo $ptype1; ?>&room_id=<?php echo $room_id; ?>';"><i class="fas fa-file-alt font16" ></i>&nbsp;รายงานทั้งหมด</button></div>
			<div class="card-body p-2">
<?php do { ?>
              <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td ><strong class="text-dark">&nbsp;&nbsp;&nbsp;<?php echo $row_med_error_type['id']; ?>. <?php echo $row_med_error_type['type_thai']; ?></strong>&ensp;<button type="button" class="btn btn-primary btn-sm"  onClick="window.location='med_error_list_type.php?&date1=<?php echo $_GET['date1']; ?>&date2=<?php echo $_GET['date2']; ?>&category1=<?php echo $category1; ?>&med_error_type1=<?php echo $row_med_error_type['id']; ?>&ptype1=<?php echo $ptype1; ?>&room_id=<?php echo $room_id; ?>&cause_id1=<?php echo $cause_id1; ?>&sub_id1=<?php echo $sub_id1; ?>'"><i class="fas fa-file-alt font16" ></i>&nbsp;<?php echo $row_med_error_type['count(tt.id)']; ?>&nbsp;รายงาน&ensp;<span class="badge badge-light"><? $percent=($row_med_error_type['count(tt.id)']/$totalRows_report)*100;  echo number_format($percent,2); ?>&nbsp;%</span></button>
                    <?php
mysql_select_db($database_hos, $hos);
$query_med_error_cause = "SELECT ec.name ,count(ec.id),ec.id FROM ".$database_kohrx.".kohrx_med_error_report as r left outer join ".$database_kohrx.".kohrx_med_error_error_cause ec on ec.id=r.error_cause WHERE ec.type_id='".$row_med_error_type['id']."' and r.date between '".$_GET['date1']."' and '".$_GET['date2']."'  ".$condition." GROUP BY ec.id";
//echo $query_med_error_cause;
$med_error_cause = mysql_query($query_med_error_cause, $hos) or die(mysql_error());
$row_med_error_cause = mysql_fetch_assoc($med_error_cause);
$totalRows_med_error_cause = mysql_num_rows($med_error_cause);


?>
                    <table width="100%" border="0" cellspacing="0" class="font14">
                      <?php do { ?>
                      <tr>
                        <td width="7%">&nbsp;</td>
                        <td width="82%" style="cursor: pointer"  onClick="window.location='med_error_list_type.php?date1=<?php echo $_GET['date1']; ?>&date2=<?php echo $_GET['date2']; ?>&category1=<?php echo $category1; ?>&med_error_type1=<?php echo $med_error_type1; ?>&cause_id1=<?php echo $row_med_error_cause['id']; ?>&sub_id1=<?php echo $sub_id1; ?>&ptype1=<?php echo $ptype1; ?>&room_id=<?php echo $room_id; ?>';">- <?php echo $row_med_error_cause['name']; ?>&nbsp;&nbsp;(<?php echo $row_med_error_cause['count(ec.id)']; ?>)<br /></td>
                        <td width="11%">&nbsp;</td>
                      </tr>
                      <?php } while ($row_med_error_cause = mysql_fetch_assoc($med_error_cause)); ?>
                    </table>
                    <? mysql_free_result($med_error_cause);
?>
                    <?
?></td>
                  <td width="40">&nbsp;</td>
                </tr>
              </table>
              <?php } while ($row_med_error_type = mysql_fetch_assoc($med_error_type)); ?>
			</div>
		</div>
		<?php } ?>
	</div>	
</div>
</form>
</div>
</body>
</html>
<?php
mysql_free_result($report);
mysql_free_result($patient);
mysql_free_result($ipd1);

if($totalRows_report<>0){

mysql_free_result($error_type);

mysql_free_result($category);

mysql_free_result($dep_report);

mysql_free_result($dep_error);

mysql_free_result($med_error_type);

mysql_free_result($error_person);

}
?>