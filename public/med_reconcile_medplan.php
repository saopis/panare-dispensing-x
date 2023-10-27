<?php 
ob_start();
session_start();
?>
<?php require_once('Connections/hos.php'); ?>
<?php
include('include/function.php');

$hos_guid=explode(",",$_POST['hos_guid']);
$totaldrug="";
for($i=0;$i<count($hos_guid);$i++){
	if($hos_guid[$i]!=""){

	mysql_select_db($database_hos, $hos);
	$query_rs_med = "select m.*,o.drugusage as drugusage1,o.sp_use,k.real_use from ".$database_kohrx.".kohrx_med_reconcile m left outer join opitemrece o on o.hos_guid=m.hos_guid left outer join ".$database_kohrx.".kohrx_drugusage_realuse k on k.drugusage=o.drugusage  where m.hos_guid='".$hos_guid[$i]."'";
	//echo $query_rs_med;
	$rs_med = mysql_query($query_rs_med, $hos) or die(mysql_error());
	$row_rs_med = mysql_fetch_assoc($rs_med);
	$totalRows_rs_med = mysql_num_rows($rs_med);
	
	$an=$_POST['an'];
	$hn=$_POST['hn'];
	$vstdate=date_th2db($_POST['vstdate']);
	$drugusage=$row_rs_med['drugusage1'];
		
	if($row_rs_med['icode']!=""){
		$icode=$row_rs_med['icode'];
		$spuse="'".$row_rs_med['sp_use']."'";
	}
	else {
		$query_rs_med_type = "select * from ".$database_kohrx.".kohrx_med_reconcile_medplan where med_plan_type='".$row_rs_med['med_plan_type']."'";
		$rs_med_type = mysql_query($query_rs_med_type, $hos) or die(mysql_error());
		$row_rs_med_type = mysql_fetch_assoc($rs_med_type);
		$totalRows_rs_med_type = mysql_num_rows($rs_med_type);
		
			$icode=$row_rs_med_type['icode'];
			$drugusage=$row_rs_med_type['drugusage'];

			mysql_select_db($database_hos, $hos);
			$query_update = "update serial set serial_no=serial_no+1 where name='sp_use'";
			$update = mysql_query($query_update, $hos) or die(mysql_error());


			mysql_select_db($database_hos, $hos);
			$query_sr = "select serial_no from serial where name='sp_use'";
			$sr = mysql_query($query_sr, $hos) or die(mysql_error());
			$row_sr = mysql_fetch_assoc($sr);
			$totalRows_sr = mysql_num_rows($sr);		
			$sp_use=$row_sr['serial_no'];
			
			//เพิ่มรายการใน sp_use
			
			mysql_select_db($database_hos, $hos);
			$query_insert = "insert into sp_use (sp_use,name1,name2,name3,user) value ('".$sp_use."','".$row_rs_med['drug_name']."','".$row_rs_med['drugusage']."','','".$_SESSION['loginname']."')";
			$insert = mysql_query($query_insert, $hos) or die(mysql_error());
			
			if($sp_use!=""){ $spuse="'".$sp_use."'"; } else { $spuse="NULL"; }

			mysql_free_result($sr);

		mysql_free_result($rs_med_type);
	}
		
	if($row_rs_med['real_use']!=""){ $qty=$row_rs_med['real_use']; } else { $qty=1; }
	
		
	mysql_free_result($rs_med);

	mysql_select_db($database_hos, $hos);
	$query_s_an = "SELECT admdoctor from ipt where an='".$_POST['an']."'";
	$s_an = mysql_query($query_s_an, $hos) or die(mysql_error());
	$row_s_an = mysql_fetch_assoc($s_an);
	$totalRows_s_an = mysql_num_rows($s_an);
	
	$doctor=$row_s_an['admdoctor'];
	
	mysql_free_result($s_an);



mysql_select_db($database_hos, $hos);
$query_update = "update serial set serial_no=serial_no+1 where name='med_plan_number'";
$update = mysql_query($query_update, $hos) or die(mysql_error());


mysql_select_db($database_hos, $hos);
$query_sr = "select serial_no from serial where name='med_plan_number'";
$sr = mysql_query($query_sr, $hos) or die(mysql_error());
$row_sr = mysql_fetch_assoc($sr);
$totalRows_sr = mysql_num_rows($sr);


mysql_select_db($database_hos, $hos);
$query_insert = "INSERT INTO medplan_ipd (med_plan_number,an,doctor,icode,qty,offdate,orderdate,orderstatus,drugusage,sp_use,frequency,first_dose_date_time,icode_type,med_interval_type_id,off_staff,first_qty,qty_2,frequency_2,usage_code,dose,unit_name,frequency_code,time_code,usage_unit_code,usage_lock,usage_line1,usage_line2,usage_line3,usage_line4,usage_shortlist,drug_hint_text,price,use_rx_pattern,rx_pattern_first_date,firstdate,firstdate_check,hos_guid,last_update,first_update,note,week_day_check,opi_acpc_id,shortlist,lang,week_day_list,is_refill,need_refill,auto_calc_first_dose,auto_first_dose_time,first_dose_cover,off_time,staff,update_datetime,is_mode3,ipd_doctor_order_detail_id,is_stat) VALUES ('".$row_sr['serial_no']."','".$an."','".$doctor."','".$icode."','".$qty."',NULL,concat(CURDATE(),' 00:00:00'),'C','".$drugusage."',".$spuse.",1,NULL,'1',0,NULL,'".$qty."',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NOW(),NOW(),NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL)";
$insert = mysql_query($query_insert, $hos) or die(mysql_error());		
		

	$totaldrug++;
	}
}
	if($insert){ echo "นำเข้าแล้ว ".$totaldrug." รายการ"; }

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>

</body>
</html>