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

    //ตรวจสอบ feild logo
	mysql_select_db($database_hos, $hos);
	$query_check = "select * from ".$database_kohrx.".kohrx_dispensing_setting where name = 'logo' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
    if($totalRows_check==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name) value ('46','logo')";
        $alter = mysql_query($query_alter, $hos) or die(mysql_error());

        mysql_select_db($database_hos, $hos);
	   $update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name) value (\'46\',\'logo\')')";
        $qupdate= mysql_query($update, $hos) or die(mysql_error());
        
			}
    mysql_free_result($check);
// ## เพิ่มฟิล์ด picture ใน kohrx_dispensing_setting
//======= 
mysql_select_db($database_hos, $hos);
$query_check = "SELECT count(*) as ccolumn 
FROM information_schema.COLUMNS 
WHERE 
	TABLE_SCHEMA = '".$database_kohrx."' 
AND TABLE_NAME = 'kohrx_dispensing_setting' 
AND COLUMN_NAME = 'picture'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

		if($row_check['ccolumn']==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "ALTER TABLE ".$database_kohrx.".`kohrx_dispensing_setting`
ADD COLUMN `picture` longblob DEFAULT NULL AFTER `value`";
		$alter = mysql_query($query_alter, $hos) or die(mysql_error());

		}	
mysql_free_result($check);

// ## เพิ่มฟิล์ด screen_time ใน kohrx_dispensing_setting
//======= 
	mysql_select_db($database_hos, $hos);
	$query_check = "select * from ".$database_kohrx.".kohrx_dispensing_setting where name = 'screen_time' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
    if($totalRows_check==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name) value ('39','screen_time')";
        $alter = mysql_query($query_alter, $hos) or die(mysql_error());

        mysql_select_db($database_hos, $hos);
	   $update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name) value (\'39\',\'screen_time\')')";
        $qupdate= mysql_query($update, $hos) or die(mysql_error());
        
			}
    mysql_free_result($check);

    //ตรวจสอบ feild icd10_drug_off
	mysql_select_db($database_hos, $hos);
	$query_check = "select * from ".$database_kohrx.".kohrx_dispensing_setting where name = 'icd10_drug_off' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
    if($totalRows_check==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name) value ('41','icd10_drug_off')";
        $alter = mysql_query($query_alter, $hos) or die(mysql_error());

        mysql_select_db($database_hos, $hos);
	   $update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name) value (\'41\',\'icd10_drug_off\')')";
        $qupdate= mysql_query($update, $hos) or die(mysql_error());
        
			}
    mysql_free_result($check);


if(isset($_POST['button10'])&&($_POST['button10']=="update"))
{
//update การเตือนหากสั่งยาเกินจำนวนที่กำหนด
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value='".$_POST['drug_count']."' where name='drug_count'";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_dispensing_setting set value=\'".$_POST['drug_count']."\' where name=\'drug_count\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());


		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value='".$_POST['cr']."' where name='Cr'";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_dispensing_setting set value=\'".$_POST['cr']."\' where name=\'Cr\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
				mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value='".$_POST['hn_length']."' where name='hn_length'";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_dispensing_setting set value=\'".$_POST['hn_length']."\' where name=\'hn_length\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());


	$paid_value="";
	for($i=0;$i<count($_POST['paidst']);$i++){
//	
	if($i+1!=count($_POST['paidst'])){ $split= ","; } else { $split="";}
	$paid_value.=$_POST['paidst'][$i].$split;
	}

		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value='$paid_value' where name='paid_value'";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_dispensing_setting set value=\'".$paid_value."\' where name=\'paid_value\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

		//update item_show
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value='".$_POST['item_show']."' where name='item_show'";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_dispensing_setting set value=\'".$_POST['item_show']."\' where name=\'item_show\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

    //update q_title
    mysql_select_db($database_hos, $hos);
    $query_rs_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value='".$_POST['q_title']."' where name='q_title'";
    $rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_dispensing_setting set value=\'".$_POST['q_title']."\' where name=\'q_title\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
    
    //update q_title2
    mysql_select_db($database_hos, $hos);
    $query_rs_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value='".$_POST['q_title2']."' where name='q_title2'";
    $rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_dispensing_setting set value=\'".$_POST['q_title2']."\' where name=\'q_title2\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

    //update q_title3
    mysql_select_db($database_hos, $hos);
    $query_rs_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value='".$_POST['q_title3']."' where name='q_title3'";
    $rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_dispensing_setting set value=\'".$_POST['q_title3']."\' where name=\'q_title3\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());    
        
    
		if(isset($_POST['important_data'])&&($_POST['important_data']=="Y")){
		//update important data
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value='N' where name='important_data'";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_dispensing_setting set value=\'N\' where name=\'important_data\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
		}
		else {
		//update important data
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value='Y' where name='important_data'";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_dispensing_setting set value=\'Y\' where name=\'important_data\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());			
		
		}

		//========= doctor_code ===========//
		if(isset($_POST['doctor_code'])&&($_POST['doctor_code']=="Y")){
		//update important data
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value='Y' where name='doctor_code_number'";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_dispensing_setting set value=\'Y\' where name=\'doctor_code_number\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
		}
		else {
		//update important data
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value=NULL where name='doctor_code_number'";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_dispensing_setting set value=NULL where name=\'doctor_code_number\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());			
		
		}

		//========== queue_list =============//
		if(isset($_POST['queue_list'])&&($_POST['queue_list']=="Y")){
		//update important data
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value='Y' where name='queue_list'";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_dispensing_setting set value=\'Y\' where name=\'queue_list\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
		}
		else {
		//update important data
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value=NULL where name='queue_list'";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_dispensing_setting set value=NULL where name=\'queue_list\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());			
		
		}

		//========== qrcode_ip =============//
		if(isset($_POST['qrcode_ip'])&&($_POST['qrcode_ip']!="")){
		//update important data
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value='".$_POST['qrcode_ip']."' where name='qrcode_ip'";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_dispensing_setting set value=\'".$_POST['qrcode_ip']."\' where name=\'qrcode_ip\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
		}
		else {
		//update important data
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value=NULL where name='qrcode_ip'";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_dispensing_setting set value=NULL where name=\'qrcode_ip\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());			
		
		}
////////////////////////////////////////////

		//update auto_logout
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value='".$_POST['auto_logout']."' where name='auto_logout'";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_dispensing_setting set value=\'".$_POST['auto_logout']."\' where name=\'auto_logout\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

		//update med_reconciliation
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value='".$_POST['med_recon']."' where id=30";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_dispensing_setting set value=\'".$_POST['med_recon']."\' where id=30')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

		//update med_reconciliation_blank
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value='".$_POST['med_recon2']."' where id=31";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_dispensing_setting set value=\'".$_POST['med_recon2']."\' where id=31')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

		//update print_sticker_type
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value='".$_POST['print_sticker_type']."' where id=32";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_dispensing_setting set value=\'".$_POST['print_sticker_type']."\' where id=31')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

		//update start_e_q
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value='".$_POST['express_q']."' where id=40";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_dispensing_setting set value=\'".$_POST['express_q']."\' where id=40')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	
    //hosxp verson
	mysql_select_db($database_hos, $hos);
	$query_check = "select * from ".$database_kohrx.".kohrx_dispensing_setting where name = 'hosxp_version' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
    if($totalRows_check==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name,value) value ('42','hosxp_version','".$_POST['hosxp_version']."')";
        $alter = mysql_query($query_alter, $hos) or die(mysql_error());

        mysql_select_db($database_hos, $hos);
	   $update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name,value) value (\'42\',\'hosxp_version\',\'".$_POST['hosxp_version']."\')')";
        $qupdate= mysql_query($update, $hos) or die(mysql_error());
        
			}
    else{
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value='".$_POST['hosxp_version']."' where id=42";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());        

        mysql_select_db($database_hos, $hos);
	   $update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_dispensing_setting set value=\'".$_POST['hosxp_version']."\' where id=42')";
        $qupdate= mysql_query($update, $hos) or die(mysql_error());        
    }
    mysql_free_result($check);

    //image server
	if($_POST['img_server']=="Y"){
		$img_server="'".$_POST['img_server']."'";
		$img_server2="\'".$_POST['img_server']."\'";
	}
	else{
		$img_server="'N'";
		$img_server2="\'N\'";
	}
	mysql_select_db($database_hos, $hos);
	$query_check = "select * from ".$database_kohrx.".kohrx_dispensing_setting where name = 'image_server' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
    if($totalRows_check==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name,value) value ('43','image_server',".$img_server.")";
        $alter = mysql_query($query_alter, $hos) or die(mysql_error());

        mysql_select_db($database_hos, $hos);
	   $update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name,value) value (\'43\',\'image_server\',".$img_server2.")')";
        $qupdate= mysql_query($update, $hos) or die(mysql_error());
        
			}
    else{
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value=".$img_server." where id=43";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());        

        mysql_select_db($database_hos, $hos);
	   $update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_dispensing_setting set value=".$img_server2." where id=43')";
        $qupdate= mysql_query($update, $hos) or die(mysql_error());        
    }
    mysql_free_result($check);

    //Lab item crcl
	mysql_select_db($database_hos, $hos);
	$query_check = "select * from ".$database_kohrx.".kohrx_dispensing_setting where name = 'crcl' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
    if($totalRows_check==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name,value) value ('44','crcl','".$_POST['crcl']."')";
        $alter = mysql_query($query_alter, $hos) or die(mysql_error());

        mysql_select_db($database_hos, $hos);
	   $update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name,value) value (\'44\',\'crcl\',\'".$_POST['crcl']."\')')";
        $qupdate= mysql_query($update, $hos) or die(mysql_error());
        
			}
    else{
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value='".$_POST['crcl']."' where id=44";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());        

        mysql_select_db($database_hos, $hos);
	   $update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_dispensing_setting set value=\'".$_POST['crcl']."\' where id=44')";
        $qupdate= mysql_query($update, $hos) or die(mysql_error());        
    }
    mysql_free_result($check);
	
    //Lab item GFR
	mysql_select_db($database_hos, $hos);
	$query_check = "select * from ".$database_kohrx.".kohrx_dispensing_setting where name = 'gfr' ";
	$check = mysql_query($query_check, $hos) or die(mysql_error());
	$row_check = mysql_fetch_assoc($check);
	$totalRows_check = mysql_num_rows($check);
	
    if($totalRows_check==0){
		mysql_select_db($database_hos, $hos);
		$query_alter = "insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name,value) value ('45','gfr','".$_POST['gfr']."')";
        $alter = mysql_query($query_alter, $hos) or die(mysql_error());

        mysql_select_db($database_hos, $hos);
	   $update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name,value) value (\'45\',\'gfr\',\'".$_POST['gfr']."\')')";
        $qupdate= mysql_query($update, $hos) or die(mysql_error());
        
			}
    else{
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value='".$_POST['gfr']."' where id=45";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());        

        mysql_select_db($database_hos, $hos);
	   $update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_dispensing_setting set value=\'".$_POST['gfr']."\' where id=45')";
        $qupdate= mysql_query($update, $hos) or die(mysql_error());        
    }
    mysql_free_result($check);
	
	
	echo "<script>parent.$.fn.colorbox.close();parent.window.location.reload();</script>";
	}


mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];
} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

mysql_select_db($database_hos, $hos);
$query_rs_setting2 = "select * from ".$database_kohrx.".kohrx_dispensing_setting where id='26'";
$rs_setting2 = mysql_query($query_rs_setting2, $hos) or die(mysql_error());
$row_rs_setting2 = mysql_fetch_assoc($rs_setting2);
$totalRows_rs_setting2 = mysql_num_rows($rs_setting2);

	if($totalRows_rs_setting2==0){
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name,value) value ('26','ตามแพทย์สั่ง','0201')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name,value) value (\'26\',\'ตามแพทย์สั่ง\',\'0201\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	
	}
mysql_free_result($rs_setting2);

//เพิ่ม record item_show
mysql_select_db($database_hos, $hos);
$query_rs_setting3 = "select * from ".$database_kohrx.".kohrx_dispensing_setting where id='28'";
$rs_setting3 = mysql_query($query_rs_setting3, $hos) or die(mysql_error());
$row_rs_setting3 = mysql_fetch_assoc($rs_setting3);
$totalRows_rs_setting3 = mysql_num_rows($rs_setting3);

	if($totalRows_rs_setting3==0){
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name,value) value ('28','item_show','2')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name,value) value (\'28\',\'item_show\',\'1\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	
	}
mysql_free_result($rs_setting3);





mysql_select_db($database_hos, $hos);
$query_rs_drug = "SELECT icode,concat(name,strength) as drugname FROM s_drugitems WHERE icode like '1%' and istatus='Y' ORDER BY name ASC";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);

mysql_select_db($database_hos, $hos);
$query_rs_pttype = "select pttype,name,paidst from pttype where isuse='Y'";
$rs_pttype = mysql_query($query_rs_pttype, $hos) or die(mysql_error());
$row_rs_pttype = mysql_fetch_assoc($rs_pttype);
$totalRows_rs_pttype = mysql_num_rows($rs_pttype);

mysql_select_db($database_hos, $hos);
$query_rs_paidst = "select * from paidst";
$rs_paidst = mysql_query($query_rs_paidst, $hos) or die(mysql_error());
$row_rs_paidst = mysql_fetch_assoc($rs_paidst);
$totalRows_rs_paidst = mysql_num_rows($rs_paidst);

mysql_select_db($database_hos, $hos);
$query_rs_drug3 = "SELECT icode,concat(name,strength) as drugname FROM s_drugitems WHERE icode like '1%' and istatus='Y' and icode not in (select icode from ".$database_kohrx.".kohrx_drug_pulse) ORDER BY name ASC";
$rs_drug3 = mysql_query($query_rs_drug3, $hos) or die(mysql_error());
$row_rs_drug3 = mysql_fetch_assoc($rs_drug3);
$totalRows_rs_drug3 = mysql_num_rows($rs_drug3);




mysql_select_db($database_hos, $hos);
$query_show_calculate = "SELECT * FROM ".$database_kohrx.".kohrx_drugitems_calculate limit 1";
$show_calculate = mysql_query($query_show_calculate, $hos) ;

mysql_select_db($database_hos, $hos);
$query_show_drp = "select * from ".$database_kohrx.".kohrx_drp_record limit 1";
$show_drp = mysql_query($query_show_drp, $hos);

mysql_select_db($database_hos, $hos);
$query_show_couselling = "select * from ".$database_kohrx.".kohrx_couselling limit 1";
$show_couselling = mysql_query($query_show_couselling, $hos);

mysql_select_db($database_hos, $hos);
$query_show_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting limit 1";
$show_setting = mysql_query($query_show_setting, $hos);

mysql_select_db($database_hos, $hos);
$query_show_syring = "SELECT * FROM ".$database_kohrx.".kohrx_insulin_syring limit 1";
$show_syring = mysql_query($query_show_syring, $hos) ;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>
<script>
$(document).ready(function() {
	        <?php if($row_setting[43]=='Y'){ ?>
            	$('#img_server').prop('checked', true);

        	<?php } else { ?>
            	$('#img_server').prop('checked', false);
        	<?php } ?>
	
          $('#rx-person').load('dispen_rx_person.php',function(responseTxt, statusTxt, xhr){
          if(statusTxt == "success")
                // $('#counseling_indicator').hide();
           	if(statusTxt == "error")
                 alert("Error: " + xhr.status + ": " + xhr.statusText);    
              }); 

          $('#lab_list').load('dispen_lab_list.php',function(responseTxt, statusTxt, xhr){
          if(statusTxt == "success")
                // $('#counseling_indicator').hide();
           	if(statusTxt == "error")
                 alert("Error: " + xhr.status + ": " + xhr.statusText);    
              }); 

          $('#upload_logo').load('include/upload_logo.php',function(responseTxt, statusTxt, xhr){
          if(statusTxt == "success")
                // $('#counseling_indicator').hide();
           	if(statusTxt == "error")
                 alert("Error: " + xhr.status + ": " + xhr.statusText);    
              }); 
   
//auto complete รายการยา
        $( "#person" ).autocomplete({ // ใช้งาน autocomplete กับ input text id=tags
		
			minLength: 0, // กำหนดค่าสำหรับค้นหาอย่างน้อยเป็น 0 สำหรับใช้กับปุ่ใแสดงทั้งหมด
            source: "doctorcode_search.php?type=doctorcode", // กำหนดให้ใช้ค่าจากการค้นหาในฐานข้อมูล
            open:function(){
				 // เมื่อมีการแสดงรายการ autocomplete
                var valInput=$(this).val(); // ดึงค่าจาก text box id=tags มาเก็บที่ตัวแปร
                if(valInput!=""){ // ถ้าไม่ใช่ค่าว่าง
                    $(".ui-menu-item a").each(function(){ // วนลูปเรียกดูค่าทั้งหมดใน รายการ autocomplete
                        var matcher = new RegExp("("+valInput+")", "ig" ); // ตรวจสอบค่าที่ตรงกันในแต่ละรายการ กับคำค้นหา
                        var s=$(this).text();
                        var newText=s.replace(matcher, "<b>$1</b>");    //      แทนค่าที่ตรงกันเป็นตัวหนา
                        $(this).html(newText); // แสดงรายการ autocomplete หลังจากปรับรูปแบบแล้ว
                    }); 
                }
            },
            select: function( event, ui ) {
                $("#doctorcode").val(ui.item.id);
            }
        });

   
});

</script>
<style type="text/css">
.white {	color:#FFFFFF;
	font-size:12px;
	font-weight:bolder;
}
tr.grid:hover {
    background-color: #FC3;
}

tr.grid:hover td {
    background-color: transparent; /* or #000 */
}
tr.grid2:hover {
    background-color: #F96;
}

tr.grid2:hover td {
    background-color: transparent; /* or #000 */
}

body {
	margin-top: 0px;
	margin-left: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	
}
html,body{overflow:hidden;}
::-webkit-scrollbar-track {
    box-shadow: inset 0 0 10px 10px #E6E6E6;
    border: solid 3px transparent;
}

::-webkit-scrollbar-thumb {
    box-shadow: inset 0 0 10px 10px #CCCCCC;
    border: solid 3px transparent;
}
.ui-autocomplete {
	position:absolute;
		margin-top:50px;
		margin-left:10px;
		padding-right:5px;
        max-height:200px !important;
        overflow: auto !important;
    	font-family: th_saraban;
		src:url(font/thsarabunnew-webfont.woff);
		font-size:14px;

}

.switch {
  position: relative;
  display: inline-block;
  width: 40px;
  height: 24px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(16px);
  -ms-transform: translateX(16px);
  transform: translateX(16px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

</style>

</head>

<body>
<form id="form1" name="form1" method="post" action="dispen_setting.php">

<nav class="navbar navbar-dark thfont bg-info text-white">
  <!-- Navbar content -->
  <span class="card-title" style="padding-top:5px;">
<i class="fas fa-check-circle font20">&ensp;ตั้งค่าการทำงาน</i>&ensp;
<input type="submit" name="button10" id="button10" value="update" class="btn btn-primary" />
</span>
</nav>
<nav class="mt-2">
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
    <a class="nav-item nav-link active" id="nav-tab1-tab" data-toggle="tab" href="#nav-tab1" role="tab" aria-controls="nav-tab1" aria-selected="true">ตั้งค่าทั่วไป</a>
    <a class="nav-item nav-link" id="nav-tab2-tab" data-toggle="tab" href="#nav-tab2" role="tab" aria-controls="nav-tab2" aria-selected="false">เจ้าหน้าที่ห้องยา</a>
    <a class="nav-item nav-link" id="nav-tab3-tab" data-toggle="tab" href="#nav-tab3" role="tab" aria-controls="nav-tab3" aria-selected="false">Lab ที่ต้องการแสดง</a>
    <a class="nav-item nav-link" id="nav-tab4-tab" data-toggle="tab" href="#nav-tab4" role="tab" aria-controls="nav-tab4" aria-selected="false">การเรียกคิว</a>
    <a class="nav-item nav-link" id="nav-tab5-tab" data-toggle="tab" href="#nav-tab5" role="tab" aria-controls="nav-tab5" aria-selected="false">สิทธิ์การชำระเงิน</a>
    <a class="nav-item nav-link" id="nav-tab6-tab" data-toggle="tab" href="#nav-tab6" role="tab" aria-controls="nav-tab6" aria-selected="false">รูปภาพ</a>
  </div>
</nav>
<div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show active" id="nav-tab1" role="tabpanel" aria-labelledby="nav-tab1-tab">
	  <div style="padding:10px;overflow:scroll;overflow-x:hidden;overflow-y:auto; height:80vh;">
			<div class="card">
					<div class="card-header">ตั้งค่าทั่วไป</div>
				  <div class="card-body">

				  <div class="row thfont ">
					<div class="col-sm-2">HOSxp version</div>
					<div class="col-sm-auto">
						 <select name="hosxp_version" id="hosxp_version" class=" form-control form-control-sm thfont font14">
							<option value="3" <?php if (!(strcmp('3', $row_setting[42]))) {echo "selected=\"selected\"";} ?>>version 3</option>
							<option value="4" <?php if (!(strcmp('4', $row_setting[42]))) {echo "selected=\"selected\"";} ?>>version 4</option>
						</select>

					  </div>
				   </div>
					<div class="row mt-2 thfont">
						<label class="col-form-label col-form-label col-sm-2 ">แยก image server</label>
								<div class="col-sm-auto">
									<label class="switch">
									<input type="checkbox" id="img_server" name='img_server' value='Y'/>
									<span class="slider round"></span>
									</label>
								</div>
					</div>

					  <div class="row thfont mt-2 ">
					<div class="col-sm-2">หลัก HN</div>
					<div class="col-sm-auto">
						 <select name="hn_length" id="hn_length" class=" form-control form-control-sm thfont font14">
						  <option value="05" <?php if (!(strcmp('05', $row_setting[24]))) {echo "selected=\"selected\"";} ?>>5</option>
						  <option value="06" <?php if (!(strcmp('06', $row_setting[24]))) {echo "selected=\"selected\"";} ?>>6</option>
						  <option value="07" <?php if (!(strcmp('07', $row_setting[24]))) {echo "selected=\"selected\"";} ?>>7</option>
						  <option value="08" <?php if (!(strcmp('08', $row_setting[24]))) {echo "selected=\"selected\"";} ?>>8</option>
						  <option value="09" <?php if (!(strcmp('09', $row_setting[24]))) {echo "selected=\"selected\"";} ?>>9</option>
						</select>        
					</div>
				</div>
				<div class="row mt-2 thfont">
					<div class="col-sm-2">Lab(Serum Creatinine)</div>
					<div class="col-sm-auto"><input name="cr" type="text" id="cr" style="width:50px;" value="<?php echo $row_setting[7]; ?>" class=" form-control form-control-sm thfont font14" /></div>
				</div>
				<div class="row mt-2 thfont">
					<div class="col-sm-2">Lab(Creatinine Clearance)</div>
					<div class="col-sm-auto"><input name="crcl" type="text" id="crcl" style="width:50px;" value="<?php echo $row_setting[44]; ?>" class=" form-control form-control-sm thfont font14" /></div>
				</div>
				<div class="row mt-2 thfont">
					<div class="col-sm-2">Lab(eGFR:CKD-EPI)</div>
					<div class="col-sm-auto"><input name="gfr" type="text" id="gfr" style="width:50px;" value="<?php echo $row_setting[45]; ?>" class=" form-control form-control-sm thfont font14" /></div>
				</div>
				<div class="row mt-2 thfont">
					<div class="col-sm-2">สั่งยาได้ไม่เกิน(เม็ด)</div> 
					<div class="col-sm-1"><input name="drug_count" type="text" id="drug_count" value="<?php echo $row_setting[25]; ?>" style="width:50px;" class=" form-control form-control-sm thfont font14" /></div>
				</div>
				<div class="row mt-2">
					 <div class="col-sm-2 thfont">วิธีพิมพ์สติ๊กเกอร์</div>
					 <div class="col-sm-auto"><select name="print_sticker_type" class=" form-control form-control-sm thfont font14" id="print_sticker_type">
						<option value="1" <?php if (!(strcmp(1, $row_setting[32]))) {echo "selected=\"selected\"";} ?>>พิมพ์จากห้องจ่ายยา</option>
						<option value="2" <?php if (!(strcmp(2, $row_setting[32]))) {echo "selected=\"selected\"";} ?>>พิมพ์จากห้องตรวจ</option>
					  </select></div>
				  </div>
				<div class="row mt-2">
					 <div class="col-sm-2 thfont">การแสดงรายการ</div>
					 <div class="col-sm-auto"><select name="item_show" class=" form-control form-control-sm thfont font14" id="item_show">
						<option value="1" <?php if (!(strcmp(1, $row_setting[28]))) {echo "selected=\"selected\"";} ?>>แสดงทั้งหมด</option>
						<option value="2" <?php if (!(strcmp(2, $row_setting[28]))) {echo "selected=\"selected\"";} ?>>แสดงเฉพาะยา</option>
					  </select></div>
				  </div>
				<div class="row mt-2 thfont">
					   <div class="col-sm-auto">doctorcode เป็นตัวเลข</div>
					 <div class="col-sm-1"><input name="doctor_code" type="checkbox" id="doctor_code"  value="Y" <?php if (!(strcmp($row_setting[35],"Y"))) {echo "checked=\"checked\"";} ?> /></div>
				</div>
				<div class="row mt-2 thfont">
					 <div class="col-sm-2">auto logout</div>
					 <div class="col-sm-auto"><select name="auto_logout" id="auto_logout" class=" form-control form-control-sm thfont">
						  <option value="" <?php if (!(strcmp("", $row_setting[36]))) {echo "selected=\"selected\"";} ?>>ไม่มีการ logout อัตโนมัติ</option>
						  <option value="1800" <?php if (!(strcmp(1800, $row_setting[36]))) {echo "selected=\"selected\"";} ?>>30 นาที</option>
						  <option value="3600" <?php if (!(strcmp(3600, $row_setting[36]))) {echo "selected=\"selected\"";} ?>>1 ชั่วโมง</option>
						  <option value="7200" <?php if (!(strcmp(7200, $row_setting[36]))) {echo "selected=\"selected\"";} ?>>2 ชั่วโมง</option>
						  <option value="14400" <?php if (!(strcmp(14400, $row_setting[36]))) {echo "selected=\"selected\"";} ?>>4 ชั่วโมง</option>
						  <option value="28800" <?php if (!(strcmp(28800, $row_setting[36]))) {echo "selected=\"selected\"";} ?>>8 ชั่วโมง</option>
					  </select></div>

				  </div>
				  <div class="row mt-2 thfont">
					   <div class="col-sm-2">Med.reconcile</div>
					   <div class="col-sm-10"><input name="med_recon" type="text" id="med_recon" value="<?php echo $row_setting[30]; ?>" class=" form-control form-control-sm thfont" /></div>
				  </div>  
				  <div class="row mt-2 thfont">
					   <div class="col-sm-2">Med.reconcile(blank)</div>
					   <div class="col-sm-10"><input name="med_recon2" type="text" id="med_recon2" value="<?php echo $row_setting[31]; ?>" class=" form-control form-control-sm thfont" /></div>
				  </div>  
				  <div class="row mt-2 thfont">
					   <div class="col-sm-2">Q ด่วนเริ่มต้น</div>
					   <div class="col-sm-2"><input type="text" name="express_q" id="express_q" class=" form-control form-control-sm thfont"  value="<?php echo $row_setting[40]; ?>"  /></div>
				   </div>
				  <div class="row mt-2 thfont">
					   <div class="col-sm-2">QRcode IP</div>
					   <div class="col-sm-10"><input name="qrcode_ip" type="text" id="qrcode_ip"  value="<?php echo $row_setting[38]; ?>" class=" form-control form-control-sm thfont" /></div>
				   </div>
				  </div>
				</div>
	  </div>
  </div>
  <div class="tab-pane fade" id="nav-tab2" role="tabpanel" aria-labelledby="nav-tab2-tab">
	  <div style="padding:10px;overflow:scroll;overflow-x:hidden;overflow-y:auto; height:80vh;">
			<div class="card thfont"><div class="card-header"><div class="row"><div class="col-sm-auto">เจ้าหน้าที่ห้องยา</div><div class="col-sm-auto"><input type="text" class="form-control form-control-sm thfont font14" id="person" name="person" style="width:200px;" />
				  <input type="hidden" name="doctorcode" id="doctorcode" />
				</div><div class="col-am-auto"><input type="button" class="btn btn-success btn-sm" id="person-add" value="เพิ่ม"/></div></div></div>
				<div class="card-body"><div  id="rx-person"></div>
				</div>
		  </div>		  
	  </div>
	  
  </div>
  <div class="tab-pane fade" id="nav-tab3" role="tabpanel" aria-labelledby="nav-tab3-tab">
	  <div style="padding:10px;overflow:scroll;overflow-x:hidden;overflow-y:auto; height:80vh;">
		 <div id="lab_list" class="mt-2"></div>
	  </div>
	  
  </div>

<div class="tab-pane fade" id="nav-tab4" role="tabpanel" aria-labelledby="nav-tab4-tab">
	  <div style="padding:10px;overflow:scroll;overflow-x:hidden;overflow-y:auto; height:80vh;">
			<div class="card">
				<div class="card-header">ระบบเรียกคิว</div>
				<div class="card-body thfont font14">
					<div class="row">
					<div class="col-sm-2">คำขึ้นต้น</div>
					<div class="col-sm-auto"><input name="q_title" type="text" class="form-control form-control-sm thfont" id="q_title" value="<?php echo $row_setting[18]; ?>" /></div>
					</div>

					<div class="row mt-2">
					<div class="col-sm-2">คำกลาง</div>
					<div class="col-sm-auto"><input name="q_title2" type="text" class="form-control form-control-sm thfont" id="q_title2" value="<?php echo $row_setting[19]; ?>"/></div>
					</div>

					<div class="row mt-2">
					<div class="col-sm-2">คำลงท้าย</div>
					<div class="col-sm-auto"><input name="q_title3" type="text" class="form-control form-control-sm thfont" id="q_title3" value="<?php echo $row_setting[20]; ?>"/></div>
					</div>

				</div>
			</div>	 
	  </div>
	  
  </div>

  <div class="tab-pane fade" id="nav-tab5" role="tabpanel" aria-labelledby="nav-tab5-tab">
		<div style="padding:10px;overflow:scroll;overflow-x:hidden;overflow-y:auto; height:80vh;">
		<div class="card mt-2">
			<div class="card-header">สิทธิที่ต้องชำระเงิน</div>
			<div class="card-body thfont font14">
				<div class="row">
				<?php if ($totalRows_rs_pttype > 0) { // Show if recordset not empty 
				$count = 0;
				while ($row_rs_pttype = mysql_fetch_assoc($rs_pttype)){ 
				if(in_array($row_rs_pttype['pttype'],explode(',',$row_setting[4]))) { $checked= "checked=\"checked\""; } else{$checked="";}
				if ($count && $count % 3 == 0) echo '</div><div class="row">';
				echo '<div class="col-sm-4" style="padding:5px;"><div class="custom-control custom-checkbox mb-3"><input type="checkbox" class="custom-control-input" name="paidst[]" id="paidst_'.$count.'" '.$checked.' " value="'.$row_rs_pttype['pttype'].'"><label class="custom-control-label" for="paidst_'.$count.'">'.$row_rs_pttype['name'].'</label></div></div>';
				$count++;
			}
		}
				?>
				</div>
			</div>
		</div>



		</div>
  </div>

<div class="tab-pane fade" id="nav-tab6" role="tabpanel" aria-labelledby="nav-tab6-tab">
	  <div style="padding:10px;overflow:scroll;overflow-x:hidden;overflow-y:auto; height:80vh;">
			<div class="card" >
				<div class="card-header">จัดการรูปภาพ</div>
				<div class="card-body thfont font14">
                    <iframe width="100%" style="height:50vh;" scrolling="no" src="include/upload_logo.php" frameborder="0"></iframe>
                </div>
          </div>
	  </div>
	  
  </div>

    
</div>

</form>
</body>
</html>
<?php

mysql_free_result($rs_setting);

mysql_free_result($rs_drug);

mysql_free_result($rs_pttype);

mysql_free_result($rs_paidst);


if($show_calculate){mysql_free_result($show_calculate);}

if($show_setting){ mysql_free_result($show_setting);}

if($show_drp){ mysql_free_result($show_drp);}

if($show_couselling){ mysql_free_result($show_couselling);}
?>
