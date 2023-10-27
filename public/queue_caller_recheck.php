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

$get_ip=$_SERVER["REMOTE_ADDR"]; 
	
	date_default_timezone_set('Asia/Bangkok');
	mysql_select_db($database_hos, $hos);
	$query_rs_room = "select room_name,r.id,c.caller_method from ".$database_kohrx.".kohrx_queue_caller_channel c left outer join ".$database_kohrx.".kohrx_queue_caller_room r on r.id=c.room_id where ip='".$get_ip."'";
	$rs_room = mysql_query($query_rs_room, $hos) or die(mysql_error());
	$row_rs_room = mysql_fetch_assoc($rs_room);
	$totalRows_rs_room = mysql_num_rows($rs_room);

	/// clear list
	if(isset($_GET['do'])&&($_GET['do']=="clear")){
		mysql_select_db($database_hos, $hos);
		$query_delete = "delete from ".$database_kohrx.".kohrx_queue_caller_list where room_id='".$row_rs_room['id']."'";
		$rs_delete = mysql_query($query_delete, $hos) or die(mysql_error());
	
	}


	mysql_select_db($database_hos, $hos);
	$query_rs_room2 = "select * from ".$database_kohrx.".kohrx_queue_caller_server_check where ip='".$get_ip."'";
	$rs_room2 = mysql_query($query_rs_room2, $hos) or die(mysql_error());
	$row_rs_room2 = mysql_fetch_assoc($rs_room2);
	$totalRows_rs_room2 = mysql_num_rows($rs_room2);

	if($totalRows_rs_room2<>0){
	mysql_select_db($database_hos, $hos);
	$query_update = "update ".$database_kohrx.".kohrx_queue_caller_server_check set time_update=NOW(),date_update=NOW(),room_id='".$row_rs_room['id']."' where ip='".$get_ip."'";
	$update = mysql_query($query_update, $hos) or die(mysql_error());
	}
	else{
	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into ".$database_kohrx.".kohrx_queue_caller_server_check (ip,time_update,date_update,room_id) values ('".$get_ip."',NOW(),NOW(),'".$row_rs_room['id']."')";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());		
	}
	//ลบ server ที่มีผลต่างเวลาน้อยกว่า 30 วินาที
	mysql_select_db($database_hos, $hos);
	$query_rs_delete = "delete from ".$database_kohrx.".kohrx_queue_caller_server_check where TIME_TO_SEC(TIMEDIFF(NOW(),concat(date_update,' ',time_update)))>=30 ";
	$rs_delete = mysql_query($query_rs_delete, $hos) or die(mysql_error());

	

?>
<?php

function num2wordsThai($num){   
    $num=str_replace(",","",$num);
    $num_decimal=explode(".",$num);
    $num=$num_decimal[0];
    $returnNumWord;   
    $lenNumber=strlen($num);   
    $lenNumber2=$lenNumber-1;   
    $kaGroup=array("","สิบ","ร้อย","พัน","หมื่น","แสน","ล้าน","สิบ","ร้อย","พัน","หมื่น","แสน","ล้าน");   
    $kaDigit=array("","หนึ่ง","สอง","สาม","สี่","ห้า","หก","เจ็ด","แปด","เก้า");   
    $kaDigitDecimal=array("ศูนย์","หนึ่ง","สอง","สาม","สี่","ห้า","หก","เจ็ด","แปด","เก้า");   
    $ii=0;   
    for($i=$lenNumber2;$i>=0;$i--){   
        $kaNumWord[$i]=substr($num,$ii,1);   
        $ii++;   
    }   
    $ii=0;   
    for($i=$lenNumber2;$i>=0;$i--){   
        if(($kaNumWord[$i]==2 && $i==1) || ($kaNumWord[$i]==2 && $i==7)){   
            $kaDigit[$kaNumWord[$i]]="ยี่";   
        }else{   
            if($kaNumWord[$i]==2){   
                $kaDigit[$kaNumWord[$i]]="สอง";        
            }   
            if(($kaNumWord[$i]==1 && $i<=2 && $i==0) || ($kaNumWord[$i]==1 && $lenNumber>6 && $i==6)){   
                if($kaNumWord[$i+1]==0){   
                    $kaDigit[$kaNumWord[$i]]="หนึ่ง";      
                }else{   
                    $kaDigit[$kaNumWord[$i]]="เอ็ด";       
                }   
            }elseif(($kaNumWord[$i]==1 && $i<=2 && $i==1) || ($kaNumWord[$i]==1 && $lenNumber>6 && $i==7)){   
                $kaDigit[$kaNumWord[$i]]="";   
            }else{   
                if($kaNumWord[$i]==1){   
                    $kaDigit[$kaNumWord[$i]]="หนึ่ง";   
                }   
            }   
        }   
        if($kaNumWord[$i]==0){   
            if($i!=6){
                $kaGroup[$i]="";   
            }
        }   
        $kaNumWord[$i]=substr($num,$ii,1);   
        $ii++;   
        $returnNumWord.=$kaDigit[$kaNumWord[$i]].$kaGroup[$i];   
    }      
    if(isset($num_decimal[1])){
        $returnNumWord.="จุด";
        for($i=0;$i<strlen($num_decimal[1]);$i++){
                $returnNumWord.=$kaDigitDecimal[substr($num_decimal[1],$i,1)];  
        }
    }       
    return $returnNumWord;   
}   
?>
<?

mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

mysql_free_result($rs_setting);
///ค้นหาข้อมูลใน kohrx_queue_liset
	mysql_select_db($database_hos, $hos);
	$query_rs_detect = "select l.id,l.hn,p.pname,p.fname,p.lname,n.channel_name,l.call_datetime,n.q_show,l.main_dep_queue,n.id as channel_id from ".$database_kohrx.".kohrx_queue_caller_list l  left outer join patient p on p.hn=l.hn left outer join ".$database_kohrx.".kohrx_queue_caller_channel_name n on n.id=l.channel_id where called is NULL and l.room_id='".$row_rs_room['id']."' and l.call_server='Y' order by l.call_datetime ASC limit 1";
	$rs_detect = mysql_query($query_rs_detect, $hos) or die(mysql_error());
	$row_rs_detect = mysql_fetch_assoc($rs_detect);
	$totalRows_rs_detect = mysql_num_rows($rs_detect);

	
	$q_show=$row_rs_detect['q_show'];
	
	
	//ค้นหา queue
	mysql_select_db($database_hos, $hos);
	$query_rs_queued = "select * from ".$database_kohrx.".kohrx_queued where hn='".$row_rs_detect['hn']."' and substr(queue_datetime,1,10)= substr('".$row_rs_detect['call_datetime']."',1,10) and room_id='".$row_rs_room['id']."' order by queue DESC limit 1";
	$rs_queued = mysql_query($query_rs_queued, $hos) or die(mysql_error());
	$row_rs_queued = mysql_fetch_assoc($rs_queued);
	$totalRows_rs_queued = mysql_num_rows($rs_queued);
		$queued_number=$row_rs_queued['queue'];
		$queued=num2wordsThai($row_rs_queued['queue']);
		$q_express=$row_rs_queued['q_express'];
	
	mysql_free_result($rs_queued);

//แสดงรายชื่อต่อไป
	mysql_select_db($database_hos, $hos);
$query_rs_detect2 = "select l.id,l.hn,l.patient_name,c.channel,l.call_datetime,n.channel_name,n.q_show from ".$database_kohrx.".kohrx_queue_caller_list l left outer join ".$database_kohrx.".kohrx_queue_caller_channel c on c.id=l.channel_id left outer join ".$database_kohrx.".kohrx_queue_caller_channel_name n on n.id=l.channel_id where called is NULL and l.room_id='".$row_rs_room['id']."' and l.call_server='Y' group by l.hn,l.call_datetime order by id DESC limit 1,10";
$rs_detect2 = mysql_query($query_rs_detect2, $hos) or die(mysql_error());
$row_rs_detect2 = mysql_fetch_assoc($rs_detect2);
$totalRows_rs_detect2 = mysql_num_rows($rs_detect2);

if($totalRows_rs_detect<>0){

mysql_select_db($database_hos, $hos);
$query_rs_now = "select (select CURTIME()) as timenow,(select time_left from ".$database_kohrx.".kohrx_queue_caller_time_left where room_id='".$row_rs_room['id']."') as timeleft";
$rs_now = mysql_query($query_rs_now, $hos) or die(mysql_error());
$row_rs_now = mysql_fetch_assoc($rs_now);
$totalRows_rs_now = mysql_num_rows($rs_now);

$timenow=$row_rs_now['timenow'];
$timeleft=$row_rs_now['timeleft'];

mysql_free_result($rs_now);

if($timenow>$timeleft){

mysql_select_db($database_hos, $hos);
$query_rs_roomcheck = "select * from ".$database_kohrx.".kohrx_queue_caller_time_left where room_id='".$row_rs_room['id']."'";
$rs_roomcheck = mysql_query($query_rs_roomcheck, $hos) or die(mysql_error());
$row_rs_roomcheck = mysql_fetch_assoc($rs_roomcheck);
$totalRows_rs_roomcheck = mysql_num_rows($rs_roomcheck);

if($totalRows_rs_roomcheck==0){
	mysql_select_db($database_hos, $hos);
	$query_update = "insert into ".$database_kohrx.".kohrx_queue_caller_time_left (room_id) value ('".$row_rs_room['id']."')";
	$rs_update = mysql_query($query_update, $hos) or die(mysql_error());	
	}

mysql_select_db($database_hos, $hos);
$query_rs_time = "update ".$database_kohrx.".kohrx_queue_caller_time_left set time_left=ADDTIME(NOW(),8) where room_id='".$row_rs_room['id']."'";
$rs_time = mysql_query($query_rs_time, $hos) or die(mysql_error());

mysql_free_result($rs_roomcheck);

//ค้นหาอายุผู้ป่วย
mysql_select_db($database_hos, $hos);
$query_rs_age = "select age_y from vn_stat where hn='".$row_rs_detect['hn']."' order by vstdate DESC LIMIT 1";
$rs_age = mysql_query($query_rs_age, $hos) or die(mysql_error());
$row_rs_age = mysql_fetch_assoc($rs_age);
$totalRows_rs_age = mysql_num_rows($rs_age);
$age=$row_rs_age['age_y'];
mysql_free_result($rs_age);

	//ค้นหาการเรียกคำนำหน้าชื่อ
	mysql_select_db($database_hos, $hos);
	$query_rs_pname = "select * from ".$database_kohrx.".kohrx_queue_caller_pname l where pname='".$row_rs_detect['pname']."'";
	$rs_pname = mysql_query($query_rs_pname, $hos) or die(mysql_error());
	$row_rs_pname = mysql_fetch_assoc($rs_pname);
	$totalRows_rs_pname = mysql_num_rows($rs_pname);
	
	if($totalRows_rs_detect<>0){
	/// ถ้าค้นแล้วมีคิวที่จะต้องเรียก
		if($totalRows_rs_pname<>0){
			/// ถ้าคำนำหน้าชื่อต้องเรียกแบบพิเศษ
			if($row_rs_pname['monk']=="Y"){
				$ppname="นิมนต์";
				}
			else {
				$ppname="ขอเชิญ";
				if($row_rs_pname['parent_call']=="Y"&&$age<12){
					$ppname.="ผู้ปกครอง";
					$ppname2="ผู้ปกครอง";
				}

				}
				$prefix=$ppname.$row_rs_pname['pname_call'];
				$prefix2=$ppname2.$row_rs_pname['pname_call'];
			}
		else {
			$prefix="ขอเชิญคุณ";
			$prefix2="คุณ";
			}
	
	//ค้นหาชื่อ
	mysql_select_db($database_hos, $hos);
	$query_rs_patient = "select * from ".$database_kohrx.".kohrx_queue_patient_name_spell where name='".$row_rs_detect['fname']."'";
	$rs_patient = mysql_query($query_rs_patient, $hos) or die(mysql_error());
	$row_rs_patient = mysql_fetch_assoc($rs_patient);
	$totalRows_rs_patient = mysql_num_rows($rs_patient);
	if($totalRows_rs_patient<>0){
		$firstname=$row_rs_patient['spell'];
	}
	else {$firstname=$row_rs_detect['fname'];}

//ค้นหานามสกุล
	mysql_select_db($database_hos, $hos);
	$query_rs_patient2 = "select * from ".$database_kohrx.".kohrx_queue_patient_name_spell where name='".$row_rs_detect['lname']."'";
	$rs_patient2 = mysql_query($query_rs_patient2, $hos) or die(mysql_error());
	$row_rs_patient2 = mysql_fetch_assoc($rs_patient2);
	$totalRows_rs_patient2 = mysql_num_rows($rs_patient2);
	if($totalRows_rs_patient2<>0){
		$lastname=$row_rs_patient2['spell'];
	}
	else {$lastname=$row_rs_detect['lname'];}

//ดำเนินการกำหนดวิธีเรียกคิว
	$q_number="";
	if($row_rs_detect['main_dep_queue']!=""){
		//ถ้าตั้งค่าเรียกเป็นชื่อ
		if($row_rs_room['caller_method']==1){
		$text = $prefix.$firstname." ".$lastname.$row_setting[19].$row_rs_detect['channel_name'].$row_setting[20];		
		}
		//ถ้าตั้งค่าเรียกเป็นคิว
		else if($row_rs_room['caller_method']==2){
		$text = "ขอเชิญคิวหมายเลข--".num2wordsThai($row_rs_detect['main_dep_queue'])."ที่".$row_rs_detect['channel_name'].$row_setting[20];
		}
		//ถ้าตั้งค่าเรียกเป็นคิว+ชื่อ
		else if($row_rs_room['caller_method']==3){
		$text = "ขอเชิญคิวหมายเลข--".num2wordsThai($row_rs_detect['main_dep_queue']).$prefix2.$firstname." ".$lastname.$row_setting[19].$row_rs_detect['channel_name'].$row_setting[20];
		}

	}
	else if($row_rs_detect['main_dep_queue']==""||$row_rs_detect['main_dep_queue']==NULL){
		if(($queued=="") or ($q_show!='Y')){
		$text = $prefix.$firstname."".$lastname.$row_setting[19].$row_rs_detect['channel_name'].$row_setting[20];
		}
		else if((($queued!="")&&($q_express!='E')) and ($q_show=='Y')){
				//ถ้าตั้งค่าเรียกเป็นชื่อ
		if($row_rs_room['caller_method']==1){
		$text = $prefix.$firstname." ".$lastname."".$row_setting[19].$row_rs_detect['channel_name'].$row_setting[20];		
		}
		//ถ้าตั้งค่าเรียกเป็นคิว
		else if($row_rs_room['caller_method']==2){
		$text = "ขอเชิญคิวหมายเลข--".$queued."ที่".$row_rs_detect['channel_name'].$row_setting[20];
		}
		//ถ้าตั้งค่าเรียกเป็นคิว+ชื่อ
		else if($row_rs_room['caller_method']==3){
		$text = "ขอเชิญคิวหมายเลข--".$queued.$prefix2.$firstname." ".$lastname.$row_setting[19].$row_rs_detect['channel_name'].$row_setting[20];
		}
		$q_number="Y";
		}
		else if((($queued!="")&&($q_express=='E')) or ($q_show!='Y')){
		//ถ้าตั้งค่าเรียกเป็นชื่อ
		if($row_rs_room['caller_method']==1){
		$text = $prefix.$firstname." ".$lastname.$row_setting[19].$row_rs_detect['channel_name'].$row_setting[20];		
		}
		//ถ้าตั้งค่าเรียกเป็นคิว
		else if($row_rs_room['caller_method']==2){
		$text = "ขอเชิญคิวหมายเลข--".$queued."ที่".$row_rs_detect['channel_name'].$row_setting[20];
		}
		//ถ้าตั้งค่าเรียกเป็นคิว+ชื่อ
		else if($row_rs_room['caller_method']==3){
		$text = "ขอเชิญคิวหมายเลข--".$queued.$prefix2.$firstname." ".$lastname.$row_setting[19].$row_rs_detect['channel_name'].$row_setting[20];
		}
		$q_number="Y";
		}
		//ถ้ามีการเรียกคิวที่ห้องจ่ายยา ให้ทำการ update สถานะว่ามีการเรียกแล้ว
		if($q_number=="Y"){
	mysql_select_db($database_hos, $hos);
	$query_rs_update = "update ".$database_kohrx.".kohrx_queued set called_channel='".$row_rs_detect['channel_id']."',called_datetime=NOW() where room_id='".$row_rs_room['id']."' and queue=".$queued_number;
	$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());
		
			}
	}
// update รายชื่อที่เรียกไปแล้วให้เติม Y ลงใน called
	mysql_select_db($database_hos, $hos);
	$query_update = "update ".$database_kohrx.".kohrx_queue_caller_list set called='Y' where id='".$row_rs_detect['id']."'";
	$update = mysql_query($query_update, $hos) or die(mysql_error());

	//ค้นหาเวลาล่าสุดของการเรียก queue
	mysql_select_db($database_hos, $hos);
	$query_rs_lastcall = "select call_datetime,hn from ".$database_kohrx.".kohrx_queue_caller_list where id='".$row_rs_detect['id']."' ";
	$rs_lastcall = mysql_query($query_rs_lastcall, $hos) or die(mysql_error());
	$row_rs_lastcall = mysql_fetch_assoc($rs_lastcall);

	mysql_select_db($database_hos, $hos);
	$query_update = "UPDATE ".$database_kohrx.".kohrx_queue_caller_history SET called='Y' WHERE hn='".$row_rs_lastcall['hn']."' and call_datetime='".$row_rs_lastcall['call_datetime']."'";
	$update = mysql_query($query_update, $hos) or die(mysql_error());
	
	mysql_free_result($rs_lastcall);
	
mysql_free_result($rs_pname);
mysql_free_result($rs_patient);
mysql_free_result($rs_patient2);

	}

	//$textlen=strlen(urlencode($text));
	
	$txt=htmlspecialchars($text);
	$txt=rawurlencode($txt);
	
	//$player="<audio id='aud' hidden controls='controls' autoplay src='data:audio/mpeg;base64,".base64_encode($html)."'></audio>";
	//echo $player;
	echo "<script>page_load('".$text."');page_load2('".$txt."');</script>";
	
/*
$lang = "th";
$url="https://translate.google.com.vn/translate_tts?ie=UTF-8&q=".$text."&tl=th&client=tw-ob";
//$url="https://translate.google.com/translate_tts?ie=UTF-8&q=".$text."&tl=th&client=tw-ob";
	echo $url;
	echo "<script>page_load('".$url."');page_load2('".$text."');</script>";
	*/
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<!-- kohrx -->
<link rel="stylesheet" href="include/kohrx/css/kohrx.css"/>
<style>
    body{
        margin: 0px;
    }
</style>
</head>

<body  >
<div style="margin:15px; margin-top:0px;">
<?php if($text!=""){ ?>
<div class="alert alert-success mt-0" role="alert"><i class="fas fa-user-circle font20"></i>&nbsp;<span class="font16 font_border">กำลังเรียก :</span><span class="font16"><?php echo $text; ?></span></div>
<?php } else { ?>
<div class="alert alert-danger mt-0" role="alert"><i class="fas fa-user-circle font20"></i>&nbsp;กำลังรอเรียก..</div>
<?php } ?>
</div>
</body>
</html>
<?php
mysql_free_result($rs_detect);
mysql_free_result($rs_detect2);
mysql_free_result($rs_room);
mysql_free_result($rs_room2);
?>