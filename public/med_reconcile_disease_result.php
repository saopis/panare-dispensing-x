<?php ob_start();?>
<?php session_start();?>
<?php require_once('Connections/hos.php'); ?>

<?php 
include('include/function.php');

if($_GET['action']=="add"){
	mysql_select_db($database_hos, $hos);
	$query_rs_disease = "select * from ".$database_kohrx.".kohrx_med_reconcile_disease where hn='".$_GET['hn']."' and vstdate='".date_th2db($_GET['vstdate'])."' and med_reconcile_disease_type='".$_GET['disease_type']."'";
	//echo $query_rs_disease;
	$rs_disease = mysql_query($query_rs_disease, $hos) or die(mysql_error());
	$row_rs_disease = mysql_fetch_assoc($rs_disease);
	$totalRows_rs_disease = mysql_num_rows($rs_disease);
	
	if($totalRows_rs_disease==0){
		mysql_select_db($database_hos, $hos);
		$query_insert = "insert into ".$database_kohrx.".kohrx_med_reconcile_disease (hn,vstdate,med_reconcile_disease_type) value ('".$_GET['hn']."','".date_th2db($_GET['vstdate'])."','".$_GET['disease_type']."')";
		$insert = mysql_query($query_insert, $hos) or die(mysql_error());	
	}
}
if($_GET['action']=="delete"){
		mysql_select_db($database_hos, $hos);
		$query_delete = "delete from ".$database_kohrx.".kohrx_med_reconcile_disease where hn='".$_GET['hn']."' and vstdate='".date_th2db($_GET['vstdate'])."' and med_reconcile_disease_type='".$_GET['disease_type']."'";
		//echo $query_delete;
		$delete = mysql_query($query_delete, $hos) or die(mysql_error());	
}

mysql_select_db($database_hos, $hos);
$query_rs_disease = "select d.*,t.med_reconcile_disease_name from ".$database_kohrx.".kohrx_med_reconcile_disease d left outer join ".$database_kohrx.".kohrx_med_reconcile_disease_type t on t.med_reconcile_disease_type=d.med_reconcile_disease_type where d.hn='".$_GET['hn']."' and d.vstdate='".date_th2db($_GET['vstdate'])."'";
//echo $query_rs_disease;
$rs_disease = mysql_query($query_rs_disease, $hos) or die(mysql_error());
$row_rs_disease = mysql_fetch_assoc($rs_disease);
$totalRows_rs_disease = mysql_num_rows($rs_disease);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<style>
span.show-delete {
    position: relative;
    float:left;
    margin-top:5px;
	margin-right: 5px;
}
span.show-delete:hover {
    background-color: red;
}
span.show-delete:hover .hover-btn {
    display: block;
	color: red;
	font-size: 18px;
}
span.show-delete .hover-btn {
    position:absolute;
    display:none;
}
span.show-delete .hover-btn {
    top:3;
    right:3px;
}</style>
</head>

<body>
<div class="row text-center">
<?php if($totalRows_rs_disease<>0){ $i=0; do{ $i++; ?>
	<div class='col-sm-auto'>
	<span class="badge badge-primary p-2 show-delete" ><?php echo  $i."&nbsp;".$row_rs_disease['med_reconcile_disease_name']; ?> 
	    <button type="button" class="close hover-btn " onClick="return disease_delete('<?php echo $row_rs_disease['hn']; ?>','<?php echo date_db2th($row_rs_disease['vstdate']); ?>','<?php echo $row_rs_disease['med_reconcile_disease_type']; ?>');" style="opacity: 1" data-dismiss="alert">
        <span aria-hidden="true" class="badge badge-light" style="width: 20px;height: 20px;">x</span>
     </button>
	</span>
	</div>
	 <?php  if($i!=$totalRows_rs_disease){ echo "&nbsp;"; }
		}while
		
		($row_rs_disease = mysql_fetch_assoc($rs_disease));
	}
?>
</div>		
</body>
</html>
<?php mysql_free_result($rs_disease); ?>