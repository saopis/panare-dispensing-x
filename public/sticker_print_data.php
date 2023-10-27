<?php require_once('Connections/hos.php'); 
$get_ip=$_SERVER["REMOTE_ADDR"];
$print_server= $_POST['print_server'];

?>
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

?>
<?php
$vn=$_POST['vn'];

mysql_select_db($database_hos, $hos);
$query_delete = "delete from doctor_order_print where vn='".$vn."'";
$delete = mysql_query($query_delete, $hos) or die(mysql_error());

///////////update print server ////////////
mysql_select_db($database_hos, $hos);
$query_update = "update ".$database_kohrx.".kohrx_queue_caller_channel set print_server='".$print_server."' where ip='".$get_ip."'";
$update = mysql_query($query_update, $hos) or die(mysql_error());

//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_queue_caller_channel set print_server=\'".$print_server."\' where ip=\'".$get_ip."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

////////////////////////////////////

for($i=0;$i<count($_POST['chk']);$i++){

//ค้นหาแพทย์ผู้สั่ง
mysql_select_db($database_hos, $hos);
$query_rs_doctor = "select o.doctor,concat(p.pname,p.fname,' ',p.lname) as patientname,concat(d.name,' ',d.strength,d.units) as drugname,o.qty,o.drugusage,d.therapeutic,p.hn,d.unitprice,p.addrpart,dc.name as doctorname,d.drugaccount,o.icode,d.hintcode,o.hos_guid,d.print_sticker_pq,d.units  from opitemrece o left outer join patient p on p.hn=o.hn left outer join drugitems d on d.icode=o.icode left outer join doctor dc on dc.code=o.doctor where vn='".$vn."' and o.icode='".$_POST['chk'][$i]."' ";
$rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
$row_rs_doctor = mysql_fetch_assoc($rs_doctor);
$totalRows_rs_doctor = mysql_num_rows($rs_doctor);
if($row_rs_doctor['drugaccount']==""){
	$drugaccount="ยานอกบัญชียาหลักแห่งชาติ";
	}
	else {$drugaccount="ยาในบัญชียาหลักแห่งชาติ";}

//ค้นหาวิธีใช้ยา
mysql_select_db($database_hos, $hos);
$query_rs_drugusage = "select * from drugusage where drugusage='".$row_rs_doctor['drugusage']."'";
$rs_drugusage = mysql_query($query_rs_drugusage, $hos) or die(mysql_error());
$row_rs_drugusage = mysql_fetch_assoc($rs_drugusage);
$totalRows_rs_drugusage = mysql_num_rows($rs_drugusage);

//ค้นหา vn ที่อยู่
mysql_select_db($database_hos, $hos);
$query_rs_vn = "select p.name,concat('หมู่ ',v.moopart,' ',t.full_name) as address,v.count_in_year,o.rx_queue from vn_stat v left outer join pttype p on p.pttype=v.pttype left outer join thaiaddress t on t.addressid=v.aid left outer join ovst o on o.vn=v.vn where v.vn='".$vn."'";
$rs_vn = mysql_query($query_rs_vn, $hos) or die(mysql_error());
$row_rs_vn = mysql_fetch_assoc($rs_vn);
$totalRows_rs_vn = mysql_num_rows($rs_vn);

//drughint
mysql_select_db($database_hos, $hos);
$query_rs_hint = "select hinttext from drughint where hc='".$row_rs_doctor['hintcode']."'";
$rs_hint = mysql_query($query_rs_hint, $hos) or die(mysql_error());
$row_rs_hint = mysql_fetch_assoc($rs_hint);
$totalRows_rs_hint = mysql_num_rows($rs_hint);

if($row_rs_vn['count_in_year']==0){
	$count_in_year="ใหม่ในปี";
	}
	else { $count_in_year=="เก่าในปี";}

//insert ลงใน doctor_order_print
	if($row_rs_doctor['print_sticker_pq']=="Y"){
	for($k=1;$k<=$row_rs_doctor['qty'];$k++){

//get serial doctor_order_print_code
mysql_select_db($database_hos, $hos);
$query_get_serial = "select get_serialnumber('doctor_order_print_code') as cc";
$get_serial = mysql_query($query_get_serial, $hos) or die(mysql_error());
$row_get_serial = mysql_fetch_assoc($get_serial);
$totalRows_get_serial = mysql_num_rows($get_serial);


//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','select get_serialnumber(\'doctor_order_print_code\') as cc')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	
	mysql_select_db($database_hos, $hos);
	$query_insert = "INSERT INTO doctor_order_print(doctor_order_print_code,vn,doctor_code,doctor_order_print_date_time,patient_name,drug_name,qty_name,line1,line2,line3,line4,shortlist,print_status,hn,print_server,item_price,qty_int,patient_age,patient_pttype_name,patient_address,doctor_name,diagnosis_name,patient_type,print_date,print_time,ward_name,bed_no,staff,print_mode,rx_queue,item_no,dep_name,icode,orderstatus,order_no,pttype,print_count,line5,
	hos_guid,med_plan_number,plan_begindate,plan_enddate,doph_id,discount) VALUES ('".$row_get_serial['cc']."','".$vn."','".$row_rs_doctor['doctor']."',NOW(),'".$row_rs_doctor['patientname']."','".$row_rs_doctor['drugname']."','1','".$row_rs_drugusage['name1']."','".$row_rs_drugusage['name2']."','".$row_rs_drugusage['name3']." (ขวดที่ ".$k.")','".$row_rs_doctor['therapeutic']."','".$row_rs_drugusage['shortlist']."','-','".$row_rs_doctor['hn']."','".$print_server."',".$row_rs_doctor['unitprice'].",1,NULL,'".$row_rs_vn['name']."','".$row_rs_doctor['addrpart'].$row_rs_vn['address']."','".$row_rs_doctor['doctorname']."','','".$count_in_year."',NULL,NULL,'".$drugaccount."','100',NULL,2,'".$row_rs_vn['rx_queue']."',".($i+1).",'ห้องจ่ายเงินผู้ป่วยนอก','".$row_rs_doctor['icode']."',NULL,NULL,NULL,NULL,'".$row_rs_hint['hinttext']."','".$row_rs_doctor['hos_guid']."',NULL,NULL,NULL,NULL,NULL)
";
        
	
	$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());
//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','INSERT INTO doctor_order_print(doctor_order_print_code,vn,doctor_code,doctor_order_print_date_time,patient_name,drug_name,qty_name,line1,line2,line3,line4,shortlist,print_status,hn,print_server,item_price,qty_int,patient_age,patient_pttype_name,patient_address,doctor_name,diagnosis_name,patient_type,print_date,print_time,ward_name,bed_no,staff,print_mode,rx_queue,item_no,dep_name,icode,orderstatus,order_no,pttype,print_count,line5,
	hos_guid,med_plan_number,plan_begindate,plan_enddate,doph_id,discount) VALUES (\'".$row_get_serial['cc']."\',\'".$vn."\',\'".$row_rs_doctor['doctor']."\',NOW(),\'".$row_rs_doctor['patientname']."\',\'".$row_rs_doctor['drugname']."\',\'1\',\'".$row_rs_drugusage['name1']."\',\'".$row_rs_drugusage['name2']."\',\'".$row_rs_drugusage['name3']." (ขวดที่ ".$k.")\',\'".$row_rs_doctor['therapeutic']."\',\'".$row_rs_drugusage['shortlist']."\',\'-\',\'".$row_rs_doctor['hn']."\',\'".$print_server."\',".$row_rs_doctor['unitprice'].",1,NULL,\'".$row_rs_vn['name']."\',\'".$row_rs_doctor['addrpart'].$row_rs_vn['address']."\',\'".$row_rs_doctor['doctorname']."\',\'\',\'".$count_in_year."\',NULL,NULL,\'".$drugaccount."\',\'100\',NULL,2,\'".$row_rs_vn['rx_queue']."\',".($i+1).",\'ห้องจ่ายเงินผู้ป่วยนอก\',\'".$row_rs_doctor['icode']."\',NULL,NULL,NULL,NULL,\'".$row_rs_hint['hinttext']."\',\'".$row_rs_doctor['hos_guid']."\',NULL,NULL,NULL,NULL,NULL)')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
		}
	}
	else{
//get serial doctor_order_print_code
mysql_select_db($database_hos, $hos);
$query_get_serial = "select get_serialnumber('doctor_order_print_code') as cc";
$get_serial = mysql_query($query_get_serial, $hos) or die(mysql_error());
$row_get_serial = mysql_fetch_assoc($get_serial);
$totalRows_get_serial = mysql_num_rows($get_serial);


//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','select get_serialnumber(\'doctor_order_print_code\') as cc')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

	mysql_select_db($database_hos, $hos);
	$query_insert = "INSERT INTO doctor_order_print(doctor_order_print_code,vn,doctor_code,doctor_order_print_date_time,patient_name,drug_name,qty_name,line1,line2,line3,line4,shortlist,print_status,hn,print_server,item_price,qty_int,patient_age,patient_pttype_name,patient_address,doctor_name,diagnosis_name,patient_type,print_date,print_time,ward_name,bed_no,staff,print_mode,rx_queue,item_no,dep_name,icode,orderstatus,order_no,pttype,print_count,line5,
	hos_guid,med_plan_number,plan_begindate,plan_enddate,doph_id,discount) VALUES ('".$row_get_serial['cc']."','".$vn."','".$row_rs_doctor['doctor']."',NOW(),'".$row_rs_doctor['patientname']."','".$row_rs_doctor['drugname']."','".$row_rs_doctor['qty']."','".$row_rs_drugusage['name1']."','".$row_rs_drugusage['name2']."','".$row_rs_drugusage['name3']."','".$row_rs_doctor['therapeutic']."','".$row_rs_drugusage['shortlist']."','-','".$row_rs_doctor['hn']."','".$print_server."',".$row_rs_doctor['unitprice'].",".$row_rs_doctor['qty'].",NULL,'".$row_rs_vn['name']."','".$row_rs_doctor['addrpart'].$row_rs_vn['address']."','".$row_rs_doctor['doctorname']."','','".$count_in_year."',NULL,NULL,'".$drugaccount."','100',NULL,2,'".$row_rs_vn['rx_queue']."',".($i+1).",'ห้องจ่ายเงินผู้ป่วยนอก','".$row_rs_doctor['icode']."',NULL,NULL,NULL,NULL,'".$row_rs_hint['hinttext']."','".$row_rs_doctor['hos_guid']."',NULL,NULL,NULL,NULL,NULL)
";
	
	$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());
//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','INSERT INTO doctor_order_print(doctor_order_print_code,vn,doctor_code,doctor_order_print_date_time,patient_name,drug_name,qty_name,line1,line2,line3,line4,shortlist,print_status,hn,print_server,item_price,qty_int,patient_age,patient_pttype_name,patient_address,doctor_name,diagnosis_name,patient_type,print_date,print_time,ward_name,bed_no,staff,print_mode,rx_queue,item_no,dep_name,icode,orderstatus,order_no,pttype,print_count,line5,
	hos_guid,med_plan_number,plan_begindate,plan_enddate,doph_id,discount) VALUES (\'".$row_get_serial['cc']."\',\'".$vn."\',\'".$row_rs_doctor['doctor']."\',NOW(),\'".$row_rs_doctor['patientname']."\',\'".$row_rs_doctor['drugname']."\',\'".$row_rs_doctor['qty']."\',\'".$row_rs_drugusage['name1']."\',\'".$row_rs_drugusage['name2']."\',\'".$row_rs_drugusage['name3']."\',\'".$row_rs_doctor['therapeutic']."\',\'".$row_rs_drugusage['shortlist']."\',\'-\',\'".$row_rs_doctor['hn']."\',\'".$print_server."\',".$row_rs_doctor['unitprice'].",".$row_rs_doctor['qty'].",NULL,\'".$row_rs_vn['name']."\',\'".$row_rs_doctor['addrpart'].$row_rs_vn['address']."\',\'".$row_rs_doctor['doctorname']."\',\'\',\'".$count_in_year."\',NULL,NULL,\'".$drugaccount."\',\'100\',NULL,2,\'".$row_rs_vn['rx_queue']."\',".($i+1).",\'ห้องจ่ายเงินผู้ป่วยนอก\',\'".$row_rs_doctor['icode']."\',NULL,NULL,NULL,NULL,\'".$row_rs_hint['hinttext']."\',\'".$row_rs_doctor['hos_guid']."\',NULL,NULL,NULL,NULL,NULL)')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
		
	}

//update print status
mysql_select_db($database_hos, $hos);
$update ="update doctor_order_print set print_status='N' where vn='$vn' and print_server='$print_server' and print_mode=2 and print_status='-'";
$rs_update = mysql_query($update, $hos) or die(mysql_error());

//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update doctor_order_print set print_status=\'N\' where vn=\'".$vn."\' and print_server=\'".$_POST['print_server']."\' and print_mode=2 and print_status=\'-\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

///////////// finish insert drug item /////////////
mysql_free_result($get_serial);

mysql_free_result($rs_doctor);

mysql_free_result($rs_drugusage);

mysql_free_result($rs_vn);

mysql_free_result($rs_hint);

	}

if($rs_insert){
//get serial doctor_order_print_code
mysql_select_db($database_hos, $hos);
$query_get_serial = "select get_serialnumber('doph_id') as cc";
$get_serial = mysql_query($query_get_serial, $hos) or die(mysql_error());
$row_get_serial = mysql_fetch_assoc($get_serial);
$totalRows_get_serial = mysql_num_rows($get_serial);


//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','select get_serialnumber(\'doph_id\') as cc')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

//insert doctor order print head
	mysql_select_db($database_hos, $hos);
	$query_insert = "INSERT INTO doctor_order_print_head (doph_id,vn,pttype,doctor_order_print_date_time,printed,doctor,sticker_count,print_server,staff,computer_name,ward,print_mode,hos_guid) VALUES 
	('".$row_get_serial['cc']."','".$vn."',NULL,NOW(),'N','".$row_rs_doctor['doctor']."','".$i."','".$_GET['print_server']."','dispensing_system','PHARM-DISPEN',NULL,NULL,NULL)
";
	$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());

//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','INSERT INTO doctor_order_print_head (doph_id,vn,pttype,doctor_order_print_date_time,printed,doctor,sticker_count,print_server,staff,computer_name,ward,print_mode,hos_guid) VALUES (\'".$row_get_serial['cc']."\',\'".$vn."\',NULL,NOW(),\'N\',\'".$row_rs_doctor['doctor']."\',\'".$i."\',\'".$_GET['print_server']."\',\'dispensing_system\',\'PHARM-DISPEN\',NULL,NULL,NULL)')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

	echo "พิมพ์รายการยาทั้งหมด $i รายการเรียบร้อย";
	}
	echo "<script>parent.$.fn.colorbox.close();</script>";

?>
<script>

 var seconds=3;// กำหนดค่าเริ่มต้น 10 วินาที
 document.getElementById("counter").value='10';//แสดงค่าเริ่มต้นใน 10 วินาที ใน text box

function display(){ //function ใช้ในการ นับถอยหลัง

    seconds-=1;//ลบเวลาทีละหนึ่งวินาทีทุกครั้งที่ function ทำงาน
 
 if(seconds==-1){return;} //เมื่อหมดเวลาแล้วจะหยุดการทำงานของ function display

    document.getElementById("counter").value=seconds; //แสดงเวลาที่เหลือ
    setTimeout("display()",1000);// สั่งให้ function display() ทำงาน หลังเวลาผ่านไป 1000 milliseconds ( 1000  milliseconds = 1 วินาที )
}
display(); //เปิดหน้าเว็บให้ทำงาน function  display()

</script>
