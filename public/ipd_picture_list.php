<?php require_once('Connections/hos.php'); ?>
<?php 
$today=date('Y-m-d H:i:s');
include('include/function.php');

if($_POST['order_save']=="save"){
mysql_select_db($database_hos, $hos);
$query_update = "update ".$database_kohrx.".kohrx_ipd_order_image set checked='Y',checked_date=NOW() where id='".$_POST['id']."'";
//echo $query_order_list;
$update = mysql_query($query_update, $hos) or die(mysql_error());    
}
if($_POST['order_save']=="unsave"){
mysql_select_db($database_hos, $hos);
$query_update = "update ".$database_kohrx.".kohrx_ipd_order_image set checked='N',checked_date=NULL where id='".$_POST['id']."'";
//echo $query_order_list;
$update = mysql_query($query_update, $hos) or die(mysql_error());    
}

if($_POST['ward']!=""){
	$condition= " and ipt.ward='".$_POST['ward']."'";
}
if($_POST['checked']!=""){
	$condition.= " and i.checked='".$_POST['checked']."'";
}
if($_POST['pt_type']!=""){
	$condition.= " and i.pt_type =".$_POST['pt_type'];
}
if($_POST['ordered']=="an"){
	$order= " i.an ".$_POST['order_type'];
}
if($_POST['ordered']=="datetime"){
	$order= " i.order_date ".$_POST['orders_type'].",i.order_time ".$_POST['orders_type'];
}

mysql_select_db($database_hos, $hos);
$query_order_list = "select i.*,ipt.hn,concat(p.pname,p.fname,' ',p.lname) as ptname,a.bedno from ".$database_kohrx.".kohrx_ipd_order_image i left outer join ipt ipt on ipt.an=i.an left outer join iptadm a on a.an=i.an left outer join patient p on p.hn=ipt.hn where capture_date between '".date_th2db($_POST['date1'])." ".$_POST['time1'].":00' and '".date_th2db($_POST['date2'])." ".$_POST['time2'].":00' ".$condition." order by pt_type ASC,".$order."";
//echo $query_order_list;
$order_list = mysql_query($query_order_list, $hos) or die(mysql_error());
$row_order_list = mysql_fetch_assoc($order_list);
$totalRows_list = mysql_num_rows($order_list);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<?php if($totalRows_list<>0){ ?>
<div class="h5">พบทั้งหมด <?php echo $totalRows_list; ?> รายการ</div>	
	<?php do{     ?>
	<?php
	if($row_order_list['pt_type']=="1"){
		$pttype_text="Admit";
		$pttype_color="success";
	}
	else if($row_order_list['pt_type']=="2"){
		$pttype_text="Cont.";
		$pttype_color="warning";
	}
	else if($row_order_list['pt_type']=="3"){
		$pttype_text="D/C";
		$pttype_color="danger";
	}	
	
	?>	

<div class="card mt-2">
    <div class="card-header">
	<div class="text-secondary " style="font-size: 11px;"><div style="font-size: 20px;"><?php if($row_order_list['pt_type']!=""){ ?><span class="badge badge-<?php echo $pttype_color; ?>" style="font-size: 20px;"><?php echo $pttype_text; ?></span>&nbsp;<?php } ?><span >AN : <?php echo $row_order_list['an']." HN: ".$row_order_list['hn']; ?></span></div><div><?php echo $row_order_list['ptname']; ?>&nbsp;เตียง: <?php echo $row_order_list['bedno']; ?>&emsp;<?php echo "วันที่สั่ง : ".date_db2th($row_order_list['order_date'])."&nbsp;เวลา ".substr($row_order_list['order_time'],0,5); ?></div></div>
    <?php if($row_order_list['checked']=="N"){ ?>
    <div ><button class="btn btn-secondary btn-sm" style="position: absolute; right: 5px; top:5px;" id="order_check" name="order_save" onClick="order_save('save','<?php echo $row_order_list['id']; ?>');"><i class="fas fa-save font20"></i> Add Chart</button></div>    
    </div>
    <?php } else { ?>
    <div ><button class="btn btn-danger btn-sm" style="position: absolute; right: 5px; top:5px;" id="order_check" name="order_unsave" onClick="order_save('unsave','<?php echo $row_order_list['id']; ?>');">ยกเลิกบันทึก</button></div>    
    </div>    
    <?php } ?>
	<div class="card-body" style="padding: 0px;">
        <?php if(DateTimeDiff($row_order_list['capture_date'],$today)<=1){ ?>
        <button type="button" class="close" onClick="if(confirm('ต้องการลบรูปนี้จริงหรือไม่')==true){ deletePic('<?php echo $row_order_list['id']; ?>','<?php echo $row_order_list['an']; ?>'); }"  style="position:absolute;right: 20px; margin-top: 5px; font-size: 30px;">&times;</button>
        <?php } ?>
        <i class="far fa-eye"  style="position:absolute;left: 20px; margin-top: 25px; font-size: 50px; color: #929292;text-shadow: 1px 1px #FFFFFF;" onClick="window.open('uploads/<?php echo $row_order_list['image_name']; ?>','_new');"></i>
		<div style=" position: absolute;margin-top:5px; margin-left:10px;"><span style="text-shadow: 1px 1px #ffffff; font-size: 12px"><?php echo datetime_db2th($row_order_list['capture_date']); ?></span></div>
		<img src="uploads/<?php echo $row_order_list['image_name']; ?>" class="rounded" style="display: flex; width: 100%;cursor: pointer;"  />
        <?php if(($row_order_list['remark']!="") and ($row_order_list['remark']!=NULL) ){ ?><div class="alert alert-warning" style="margin-bottom: 0px;" role="alert"><?php echo $row_order_list['remark']; ?>
        </div>	
        <?php } ?>
    </div>
</div>
<?php }while($row_order_list = mysql_fetch_assoc($order_list)); } ?>
</body>
</html>
<?php mysql_free_result($order_list);