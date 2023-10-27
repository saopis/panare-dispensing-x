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
mysql_select_db($database_hos, $hos);
$query_rs_list1 = "SELECT t1.patient_name,t1.channel_id,t1.hn,t1.patient_name,t1.call_datetime,q.queue,n.channel_name,n.q_show,t1.main_dep_queue
FROM ".$database_kohrx.".kohrx_queue_caller_list t1

left outer join ".$database_kohrx.".kohrx_queued q on q.hn=t1.hn and substr(q.queue_datetime,1,10)= CURDATE()
left outer join ".$database_kohrx.".kohrx_queue_caller_channel_name n on n.id=t1.channel_id
where t1.room_id='".$_GET['room_id']."' and t1.not_response is NULL and TIME_TO_SEC(TIMEDIFF(CURTIME(),SUBSTR(t1.call_datetime,11,9))) <1800 and t1.dispensed is NULL and SUBSTR(t1.call_datetime,1,10)=CURDATE() order by t1.call_datetime DESC limit 1";
$rs_list1 = mysql_query($query_rs_list1, $hos) or die(mysql_error());
$row_rs_list1 = mysql_fetch_assoc($rs_list1);
$totalRows_rs_list1 = mysql_num_rows($rs_list1);

mysql_select_db($database_hos, $hos);
$query_rs_list = "SELECT t1.patient_name,t1.channel_id,t1.hn,t1.patient_name,t1.call_datetime,q.queue,n.channel_name,n.q_show,t1.main_dep_queue
FROM ".$database_kohrx.".kohrx_queue_caller_list t1

left outer join ".$database_kohrx.".kohrx_queued q on q.hn=t1.hn and substr(q.queue_datetime,1,10)= CURDATE()
left outer join ".$database_kohrx.".kohrx_queue_caller_channel_name n on n.id=t1.channel_id
where t1.room_id='".$_GET['room_id']."' and TIME_TO_SEC(TIMEDIFF(CURTIME(),SUBSTR(t1.call_datetime,11,9))) <1800 and t1.dispensed is NULL and SUBSTR(t1.call_datetime,1,10)=CURDATE() and t1.hn != 
(

SELECT t2.hn
FROM ".$database_kohrx.".kohrx_queue_caller_list t2

left outer join ".$database_kohrx.".kohrx_queued q2 on q2.hn=t2.hn and substr(q2.queue_datetime,1,10)= CURDATE()
where t2.room_id='".$_GET['room_id']."' and TIME_TO_SEC(TIMEDIFF(CURTIME(),SUBSTR(t2.call_datetime,11,9))) <1800 and t2.dispensed is NULL and SUBSTR(t2.call_datetime,1,10)=CURDATE() order by t2.call_datetime DESC limit 1

) and t1.hn !='".$row_rs_list1['hn']."'  and t1.not_response is NULL 
group by t1.hn order by t1.call_datetime DESC limit 4";
$rs_list = mysql_query($query_rs_list, $hos) or die(mysql_error());
$row_rs_list = mysql_fetch_assoc($rs_list);
$totalRows_rs_list = mysql_num_rows($rs_list);


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
html,body{ overflow-x: hidden;}
.img-circle {
    border-radius: 50%;
}
.container2 {
   height: auto;
   overflow: hidden;
}

.left2 {
    width: 300px;
    float: left;
	height:118px;
    background-color: #069 ;
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
	background-color: #F60;
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
</head>

<body>
<?php
		do{
			if($totalRows_rs_list1<>0){
				?>
            <div style="height:296px; border-bottom:solid 20px #666666; margin-bottom:20px;" class="container4">
            	<div class="left4" align="center"  style="background-color: #F2410D">
				<div style="height:80px; background-color: #A00; font-size:45px; color:#FFFFFF" class="thfont font_bord">คิวที่เรียกล่าสุด</div>
				<div style="font-size:350px; margin-top:-155px; color:#FFFFFF;" class=" thsan-semibold"><?php if($row_rs_list1['q_show']=='Y'){ print $row_rs_list1['queue']; } ?></div>			
                </div>
                <div class="right4" style="background-color:#FFFFFF">
                <div style="height:296px; margin-top:-5px;" class="container5">

            		<div  align="center" style="border-right:solid 1px #CCCCCC;" >		
                    <div style=" font-size:60px; margin-top:5px;" class="thfont font_border" ><?php echo $row_rs_list1['patient_name']; ?></div>       
		<div style="color:#D51E42; font-size:100px; margin-top:-45px;" class="thfont font_border"><?php echo $row_rs_list1['channel_name']; ?></span></div>
		<div style="color: #36F; font-size:40px; margin-top:-50px; margin-right:50px;" class="thfont font_border"><?php echo "เวลาเรียก : ".substr($row_rs_list1['call_datetime'],10,6); ?></span></div>
                    </div>                    
                </div>
                </div>
            </div>
          <?php 	
				}
		  	}while($row_rs_list1 = mysql_fetch_assoc($rs_list1)); ?>
<div class="container2 thfont font_bord" style="height:80px; margin-left:10px; margin-bottom:10px; ">
    <div class="left2"  align="center" style="font-size:50px; color:#FFFFFF; background-color:#59B4E3">คิว</div>
    <div class="right2" style="font-size:30px; ">
            <div class="container6" style="font-size:50px; color:#FFFFFF;">
        	<div class="left6" style="background-color:#59B4E3" align="center">ชื่อ</div>
        	<div class="right6" style="background-color:#59B4E3; color:#FFF" align="center">ช่องบริการ</div>            
			</div>
    </div>    
</div>

<?php 
	if($totalRows_rs_list<>0){
		do{
				?>
    		
<div class="container2" style="height:115px; border-bottom:1px #000000 solid; margin-left:10px;">
    <div class="left2"  align="center" style="background-color:#069">
      <div style="font-size:150px; margin-top:-65px; color:#FFFFFF; padding:10px;" class="thsan-semibold">
	  <?php if($row_rs_list['q_show']=='Y'){ print $row_rs_list['queue']; } ?>
    </div>
    </div>
    <div class="right2" style="font-size:30px; ">
      <div class="container3">
        <div class="thfont font_border" style=" padding-left:10px; font-size:40px; ">
        <div class="container6">
        	<div class="left6">
				<div style="padding-left:50px;"><?php echo $row_rs_list['patient_name']; ?></div>
				<div style="margin-top:-20px; color:#999999; font-size:30px; padding-left:50px;">เวลาเรียก : <?php echo substr($row_rs_list['call_datetime'],10,6); ?></div>
            </div>
            <div class="right6"><span style=" font-size:70px; margin-top:-40px; padding-left:30px;" class="thfont font_border"><?php echo $row_rs_list['channel_name']; ?></span></div>
        </div>
		</div>        
      </div>
		
  </div>
</div>		
		<?	
				
			
		}while($row_rs_list = mysql_fetch_assoc($rs_list));
	}
?></body>
</html>
<?php
mysql_free_result($rs_list);
?>
