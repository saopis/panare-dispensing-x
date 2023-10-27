<?php require_once('Connections/hos.php'); ?>
<?php
include('include/function.php');

mysql_select_db($database_hos, $hos);
$query_order_list = "select * from ".$database_kohrx.".kohrx_ipd_order_image where an='".$_GET['an']."' order by order_date DESC,order_time DESC,capture_date DESC";
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
<?php if($totalRows_list<>0){ do{     ?>
<div class="card" style="margin-top: 10px;">
	<div class="card-body rounded" style="padding: 0px;" >
		<div style=" position: absolute;margin-top:5px; margin-left:10px;"><span style="text-shadow: 1px 1px #ffffff; font-size: 12px"><?php echo datetime_db2th($row_order_list['capture_date']); ?></span></div>
		<center>   
        <img src="uploads/<?php echo $row_order_list['image_name']; ?>" class="rounded" id="<?php echo $row_order_list['id']; ?>" style="display: flex; width: 100%; z-index: -1"  />
        </center>    
        <?php if(($row_order_list['remark']!="") and ($row_order_list['remark']!=NULL) ){ ?><div class="alert alert-warning" style="margin-bottom: 0px;" role="alert"><?php echo $row_order_list['remark']; ?>
        </div>	
        <?php } ?>
    </div>    
</div>  
<?php }while($row_order_list = mysql_fetch_assoc($order_list)); } ?>	
</div>    
</body>
</html>
<?php mysql_free_result($order_list); ?>