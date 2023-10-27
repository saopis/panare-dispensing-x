<?php require_once('Connections/hos.php'); ?>
<?php
if($_POST['med_order_number']!=""){
mysql_select_db($database_hos, $hos);
$query_rs_qty = "select med_order_qty from medpay_ipd where med_order_number='".$_POST['med_order_number']."' ";
$rs_qty = mysql_query($query_rs_qty, $hos) or die(mysql_error());
$row_rs_qty = mysql_fetch_assoc($rs_qty);
$totalRows_rs_qty = mysql_num_rows($rs_qty);

echo $row_rs_qty['med_order_qty'];

mysql_free_result($rs_qty);
    
	}
?>
