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
date_default_timezone_set("Asia/Bangkok");

$get_ip=$_SERVER["REMOTE_ADDR"];

mysql_select_db($database_hos, $hos);
$query_channel = "SELECT depcode,time_per_case,q_dep_type,room_id from ".$database_kohrx.".kohrx_queue_caller_channel q  WHERE ip='".$get_ip."'";
$channel = mysql_query($query_channel, $hos) or die(mysql_error());
$row_channel = mysql_fetch_assoc($channel);
$totalRows_channel = mysql_num_rows($channel);

mysql_select_db($database_hos, $hos);
$query_rs_max = "select q.queue,l.call_datetime from ".$database_kohrx.".kohrx_queue_caller_list l left outer join ".$database_kohrx.".kohrx_queued q on q.hn=l.hn and q.room_id=l.room_id and substr(queue_datetime,1,10)=CURDATE() where l.room_id='".$row_channel['room_id']."' and substr(l.call_datetime,1,10)=CURDATE() and l.called ='Y' order by q.queue DESC,l.main_dep_queue DESC,l.call_datetime DESC limit 1 ";
$rs_max = mysql_query($query_rs_max, $hos) or die(mysql_error());
$row_rs_max = mysql_fetch_assoc($rs_max);
$totalRows_rs_max = mysql_num_rows($rs_max);

mysql_select_db($database_hos, $hos);
$query_rs_max2 = "select max(main_dep_queue) as max_q from ovst where main_dep='".$row_channel['main_dep']."' ";
$rs_max2 = mysql_query($query_rs_max2, $hos) or die(mysql_error());
$row_rs_max2 = mysql_fetch_assoc($rs_max2);
$totalRows_rs_max2 = mysql_num_rows($rs_max2);

mysql_select_db($database_hos, $hos);
$query_rs_list1 = "SELECT t1.patient_name,t1.hn,t1.patient_name,t1.call_datetime,q.queue,n.channel_name,n.q_show,t1.main_dep_queue FROM ".$database_kohrx.".kohrx_queue_caller_list t1 left outer join ".$database_kohrx.".kohrx_queued q on q.hn=t1.hn and substr(q.queue_datetime,1,10)= CURDATE() left outer join ".$database_kohrx.".kohrx_queue_caller_channel_name n on n.id=t1.channel_id where t1.room_id='".$_GET['room_id']."' and TIME_TO_SEC(TIMEDIFF(CURTIME(),SUBSTR(t1.call_datetime,11,9))) <1800 and t1.dispensed is NULL and SUBSTR(t1.call_datetime,1,10)=CURDATE() order by t1.call_datetime DESC limit 1";
$rs_list1 = mysql_query($query_rs_list1, $hos) or die(mysql_error());
$row_rs_list1 = mysql_fetch_assoc($rs_list1);
$totalRows_rs_list1 = mysql_num_rows($rs_list1);

if($totalRows_rs_max<>0){
$selectedTime = substr($row_rs_max['call_datetime'],10,9);
}
else{
$selectedTime=date('H:i:s');
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
tr:nth-child(even) {background: #4D5C62}
tr:nth-child(odd) {background: #566169}
.container2 {
   height: auto;
   overflow: hidden;
}

.left2 {
    width: 220px;
    float: left;
	height:118px;
    background: #64B5F6;
}

.right2 {
    float: none; /* not needed, just for clarification */
    background: #ECEFF1;
    /* the next props are meant to keep this block independent from the other floated one */
    width: auto;
	height:118px;
    overflow: hidden;
}​​
.container3 {
   height: auto;
   overflow: hidden;
}

.left3 {
    width: 111px;
    float: left;
	height:118px;
}

.right3 {
    float: none; /* not needed, just for clarification */
    /* the next props are meant to keep this block independent from the other floated one */
    width: auto;
	height:118px;
    overflow: hidden;
}​​
.container4 {
   height: auto;
   overflow: hidden;
}

.left4 {
    width: 450px;
    float: left;
	height:296px;
	background-color:#1565C0;
}

.right4 {
    float: none; /* not needed, just for clarification */
    /* the next props are meant to keep this block independent from the other floated one */
    width: auto;
	height:296px;
    overflow: hidden;
}​​
.container5 {
   height: auto;
   overflow: hidden;
}

.left5 {
    width: 277px;
    float: left;
	height:296px;
}

.right5 {
    float: none; /* not needed, just for clarification */
    /* the next props are meant to keep this block independent from the other floated one */
    width: auto;
	height:296px;
    overflow: hidden;
	background-color:#FFF;
}​​
.container6 {
   height: auto;
   overflow: hidden;
}

.left6 {
    width: 500px;
    float: left;
	height:118px;
}

.right6 {
    float: none; /* not needed, just for clarification */
    /* the next props are meant to keep this block independent from the other floated one */
    width: auto;
	height:118px;
    overflow: hidden;
	background-color: #CFDA94;
	color: #000;
}​​

</style>
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
		do{
			if($totalRows_rs_list1<>0){
				?>
<div style="height:296px;" class="container4">
            	<div class="left4" align="center" >
				<div style="height:50px; background-color:#0D47A1; font-size:30px; color:#FFFFFF" class="thfont font_bord">คิวที่เรียกล่าสุด</div>
				<div style="font-size:400px; margin-top:-185px; color:#FFFFFF;" class=" thsan-semibold"><?php if($row_rs_list1['q_show']=='Y'){ print $row_rs_list1['queue']; } ?></div>			
                </div>
                <div class="right4">
                <div style="height:296px;" class="container5">
            		<div class="left5" align="center" >
                    <?
	mysql_select_db($database_hos, $hos);
 	$query_selpic = "select count(*) as cc from patient_image where hn='".$row_rs_list1['hn']."' ";
	$selpic = mysql_query($query_selpic, $hos) or die(mysql_error());
	$row_selpic = mysql_fetch_assoc($selpic);
	$totalRows_selpic = mysql_num_rows($selpic);
					if($row_selpic['cc']>0){
				mysql_select_db($database_hos, $hos);
				$query = "SELECT image as blob_img FROM patient_image where hn='".$row_rs_list1['hn']."' "; 
				$result = mysql_query($query, $hos) or die(mysql_error()); 
				$row = mysql_fetch_array($result); 
				$jpg = $row["blob_img"]; 

							?>
							<img src="data:image/jpeg;base64,<?php echo base64_encode($jpg); ?> "  width="277" height="296" vlign="middle" border="0"   /> <?php 
						}
						else {
							echo "<img src=\"images/noimage.png\" width=\"277\" height=\"296\"  />";
							}
							?>
                    </div>
            		<div class="right5" align="center" style="border-right:solid 1px #CCCCCC;" >		
                    <div style="font-size:45px; margin-top:20px;" class="thfont font_border" ><?php echo $row_rs_list1['patient_name']; ?></div>       
		<div style="color:#D51E42; font-size:100px; margin-top:-15px;" class="thfont font_border"><?php echo $row_rs_list1['channel_name']; ?></span></div>
                    </div>                    
                </div>
                </div>
            </div>
          <?php 	
				}
		  	}while($row_rs_list1 = mysql_fetch_assoc($rs_list1)); ?>
<!-- // คนที่รอเรียก // -->
<div style="background-color:#000; color: #FFF; font-size:50px; height:74px; padding-top:10px;" ><span style=" padding-left:20px;">คิวที่รอเรียก</span></div>
<div style="background-color: #666; height:43px;">
	 <div class="container2" style="height:43px;">
     	<div class="left2" align="center" style="background-color: #3C83D2; font-size:30px; color:#FFFFFF">คิวรับบริการ</div>
        <div class="right2" style="background-color: #999">
        	<div class="container3" style="height:43px;">
            <div class="left3"></div>
            <div class="right3">
            <div class="container6" style="height:43px;">
                <div class="left6" style="font-size:30px; width:600px; color:#333333;">ชื่อผู้รับบริการ/แผนกหลัก</div>
                <div class="right6" align="center" style="background-color: #C90; font-size:30px;">เวลาให้บริการ(ประมาณ)</div>
            </div>
            </div>
            </div>
        </div>
	</div>
</div>
  <?php 
  $time=$selectedTime;
  if($totalRows_rs_list1==0){
	  $page=6;
  }
  else{
		$page=4;  
	}
  	mysql_select_db($database_hos, $hos);
	$query_rs_q_order = "select p.hn,o.queue,concat(p.pname,p.fname,' ',p.lname) as ptname from ".$database_kohrx.".kohrx_queued o left outer join patient p on p.hn=o.hn  where substr(o.queue_datetime,1,10)=CURDATE() and o.room_id='".$row_channel['room_id']."' and o.queue > '".$row_rs_max['queue']."' order by o.queue ASC limit $page";
	$rs_q_order = mysql_query($query_rs_q_order, $hos) or die(mysql_error());
	$row_rs_q_order = mysql_fetch_assoc($rs_q_order);
	$totalRows_rs_q_order = mysql_num_rows($rs_q_order);
	if($totalRows_rs_q_order<>0){
	do{
	 $endTime = strtotime("+".$row_channel['time_per_case']." minutes", strtotime($time));
	 ?>
	 <div class="container2" style="height:118px; border-bottom:1px #000000 solid;">
    <div class="left2"  align="center">
      <div style="font-size:180px; margin-top:-85px; color:#000000; padding:10px;" class="thsan-semibold">
	  <?php  print $row_rs_q_order['queue'];  ?>
    </div>
    </div>
    <div class="right2" style="font-size:30px; ">
      <div class="container3">
        <div class="left3">       	<?
	mysql_select_db($database_hos, $hos);
 	$query_selpic = "select count(*) as cc from patient_image where hn='".$row_rs_q_order['hn']."' ";
	$selpic = mysql_query($query_selpic, $hos) or die(mysql_error());
	$row_selpic = mysql_fetch_assoc($selpic);
	$totalRows_selpic = mysql_num_rows($selpic);
					if($row_selpic['cc']>0){
				mysql_select_db($database_hos, $hos);
				$query = "SELECT image as blob_img FROM patient_image where hn='".$row_rs_q_order['hn']."' "; 
				$result = mysql_query($query, $hos) or die(mysql_error()); 
				$row = mysql_fetch_array($result); 
				$jpg = $row["blob_img"]; 

							?>
							<img src="data:image/jpeg;base64,<?php echo base64_encode($jpg); ?> "  width="111" height="118" vlign="middle" border="0"   /> <?php 
						}
						else {
							echo "<img src=\"images/noimage.png\" width=\"111\" height=\"118\"  />";
							}
							?>
</div>
        <div class="right3 thfont font_border" style=" padding-left:10px; font-size:40px; ">
        <div class="container6">
        	<div class="left6" style="width:590px; font-size:40px; padding-top:20px;">
				<div><?php echo $row_rs_q_order['ptname']; ?></div>
				
            </div>
            <div class="right6" align="center"><span style=" font-size:70px; margin-top:-40px; padding-left:20px;" class="thfont font_border"><?php echo date('H:i', $endTime); ?></span></div>
        </div>
		</div>        
      </div>
		
  </div>
</div>		

  <?php
	  
	  $time=date('H:i:00', $endTime);
	}while($row_rs_q_order = mysql_fetch_assoc($rs_q_order));
	}
	mysql_free_result($rs_q_order);
   ?>
</table>
</body>
</html>
<?php
mysql_free_result($channel);
mysql_free_result($rs_max);
mysql_free_result($rs_max2);
mysql_free_result($rs_list1);


?>
