<?php ob_start();?>
<?php session_start();?>
<?php require_once('Connections/hos.php'); ?>
<?php include('include/function.php'); ?>
<?php
//===== setting ==========//
mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

mysql_free_result($rs_setting);

//===== setting ==========//
if(isset($_GET['barcode'])&&($_GET['barcode']!="")){
//total icode	
mysql_select_db($database_hos, $hos);
$query_s_sum = "select hos_guid from opitemrece where vn='".$_GET['vn']."' and icode like '1%'";
$s_sum = mysql_query($query_s_sum, $hos) or die(mysql_error());
$row_s_sum = mysql_fetch_assoc($s_sum);
$totalRows_s_sum = mysql_num_rows($s_sum);

$totaldrug=$totalRows_s_sum;

mysql_free_result($s_sum);

mysql_select_db($database_hos, $hos);
$query_s_complete = "select * from ".$database_kohrx.".kohrx_drug_checked where vn='".$_GET['vn']."'";
$s_complete = mysql_query($query_s_complete, $hos) or die(mysql_error());
$row_s_complete = mysql_fetch_assoc($s_complete);
$totalRows_s_complete = mysql_num_rows($s_complete);

if($totaldrug<>0&&($totaldrug==$totalRows_s_complete)){
	$checkcomplete="Y";
}
else{
	$checkcomplete="N";
}

/*//first check complete	
mysql_select_db($database_hos, $hos);
$query_s_complete = "select * from ".$database_kohrx.".kohrx_drug_checked where vn='".$_GET['vn']."'";
$s_complete = mysql_query($query_s_complete, $hos) or die(mysql_error());
$row_s_complete = mysql_fetch_assoc($s_complete);
$totalRows_s_complete = mysql_num_rows($s_complete);

if($totaldrug=$totalRows_s_complete)
$firstcheckcomplete=*/
	
	mysql_select_db($database_hos, $hos);
	$query_barcode = "select hos_guid,vstdate from opitemrece where substr(md5(hos_guid),-10)='".$_GET['barcode']."' and vn='".$_GET['vn']."'";
//	echo $query_barcode;
	$rs_barcode = mysql_query($query_barcode, $hos) or die(mysql_error());
	$row_rs_barcode = mysql_fetch_assoc($rs_barcode);
	$totalRows_rs_barcode= mysql_num_rows($rs_barcode);

		$hos_guid=$row_rs_barcode['hos_guid'];
		$vstdate=$row_rs_barcode['vstdate'];
		$div=$_GET['barcode'];
	mysql_free_result($rs_barcode);
//	exit();




//exit();	
echo "<script>drug_list_load_vn_check('".$_GET['vn']."','".$hos_guid."','".$div."','".date_db2th($vstdate)."','".$checkcomplete."');</script>";
	
if($_GET['checkstatus']!=$checkcomplete){
	echo "<script>drug_list_load_vn('".$_GET['vn']."','".date_db2th($vstdate)."')</script>";
}
mysql_free_result($s_complete);
	
	exit();
}
//บันทึกการเช็คยา
if((isset($_GET['checkcode'])&&($_GET['checkcode']!=""))||$hos_guid!=""){
		if($hos_guid<>""){
			$checkcode=$hos_guid;
		}
		else{
			$checkcode=$_GET['checkcode'];
		}
		
		
		mysql_select_db($database_hos, $hos);
		$query_rs_search = "select k.*,v.vstdate from ".$database_kohrx.".kohrx_drug_checked k left outer join vn_stat v on v.vn=k.vn where k.vn='".$_GET['vn']."' and k.hos_guid='".$checkcode."'";
		$rs_search = mysql_query($query_rs_search, $hos) or die(mysql_error());
		$row_rs_search = mysql_fetch_assoc($rs_search);
		$totalRows_rs_search = mysql_num_rows($rs_search);
//	ถ้าว่างให้บันทึก
			if($totalRows_rs_search==0){
				mysql_select_db($database_hos, $hos);
				$query_insert = "insert into ".$database_kohrx.".kohrx_drug_checked (vn,hos_guid,doctorcode) value ('".$_GET['vn']."','".$checkcode."','".$_SESSION['doctorcode']."')";
				$insert = mysql_query($query_insert, $hos) or die(mysql_error());			
			}
//ถ้าไม่ว่างให้ลบ	
	else{
				mysql_select_db($database_hos, $hos);
				$query_delete = "delete from ".$database_kohrx.".kohrx_drug_checked where hos_guid='".$checkcode."'";
				$delete = mysql_query($query_delete, $hos) or die(mysql_error());				
				if($delete){
//					echo "<script>drug_list_load_vn_check('".$_GET['vn']."','".date_db2th($_GET['vstdate'])."');exit();</script>";
				}
	}

		mysql_free_result($rs_search);
	if($hos_guid!=""){
		echo "<script>drug_list_load_vn('".$_GET['vn']."','".date_db2th($vstdate)."');</script>";
	}
	
}

mysql_select_db($database_hos, $hos);
$query_s_drug = "select * from ".$database_kohrx.".kohrx_drug_checked where vn='".$_GET['vn']."' and hos_guid='".$checkcode."'";
$s_drug = mysql_query($query_s_drug, $hos) or die(mysql_error());
$row_s_drug = mysql_fetch_assoc($s_drug);
$totalRows_s_drug = mysql_num_rows($s_drug);

mysql_select_db($database_hos, $hos);
$query_s_sum = "select hos_guid from opitemrece where vn='".$_GET['vn']."' and icode like '1%'";
$s_sum = mysql_query($query_s_sum, $hos) or die(mysql_error());
$row_s_sum = mysql_fetch_assoc($s_sum);
$totalRows_s_sum = mysql_num_rows($s_sum);

$totaldrug=$totalRows_s_sum;

mysql_free_result($s_sum);

mysql_select_db($database_hos, $hos);
$query_s_complete = "select * from ".$database_kohrx.".kohrx_drug_checked where vn='".$_GET['vn']."'";
$s_complete = mysql_query($query_s_complete, $hos) or die(mysql_error());
$row_s_complete = mysql_fetch_assoc($s_complete);
$totalRows_s_complete = mysql_num_rows($s_complete);

if($totaldrug<>0&&($totaldrug==$totalRows_s_complete)){
	$checkcomplete="Y";
}
else{
	$checkcomplete="N";
}


if($_GET['checkstatus']!=$checkcomplete){
	echo "<script>drug_list_load_vn('".$_GET['vn']."','".$_GET['vstdate']."')</script>";
}

mysql_free_result($s_complete);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<!--
<script>
function CheckComplete(action){
	if(action=="Y"){
		$('#checkcomplete').hide();
		}
	else{
		$('#checkcomplete').show();		
		}
	alert(action);
}		
</script>	
-->
</head>

<body>
<i class="fas fa-check " style="cursor: pointer;color:<?php echo ifnotempty($row_s_drug['hos_guid'],"green;"); echo ifempty($row_s_drug['hos_guid'],"#DEDEDE;" ); ?>" onClick="<?php if($row_s_drug['hos_guid']==""){ echo "drug_list_load_vn_check('".$_GET['vn']."','". $_GET['checkcode']."','".$_GET['div']."','".$_GET['vstdate']."','".$checkcomplete."')";}else{ echo "if(confirm('ต้องการยกเลิกเช็ค!! จริงหรือไม่?')==true){ drug_list_load_vn_check('".$_GET['vn']."','". $_GET['checkcode']."','".$_GET['div']."','".$_GET['vstdate']."','".$checkcomplete."') }"; } ?>" ></i>	
</body>
</html>
<?php mysql_free_result($s_drug); ?>
