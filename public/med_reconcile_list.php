<?php ob_start();?>
<?php session_start();?>
<?php require_once('Connections/hos.php'); ?>
<?php
include('include/function.php');
mysql_select_db($database_hos, $hos);
$query_rs_med = "select m.hn,m.vstdate2,concat(p.pname,p.fname,' ',p.lname) as ptname,count(*) as count_drug from ".$database_kohrx.".kohrx_med_reconcile m left outer join patient p on p.hn=m.hn group by m.vstdate2,m.hn order by m.vstdate2 DESC,m.hn";
//echo $query_rs_med;
$rs_med = mysql_query($query_rs_med, $hos) or die(mysql_error());
$row_rs_med = mysql_fetch_assoc($rs_med);
$totalRows_rs_med = mysql_num_rows($rs_med);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<script>
$(document).ready(function() {
    var printCounter = 0;
    $('#tables').append('<caption style="caption-side: bottom"></caption>');
    $('#tables').DataTable();
});
</script>

</head>

<body>
  <table id="tables" width="100%" border="0" class="table table-sm table-striped table-hover ">
    <thead>
    <tr>
		<th>ลำดับ</th>
		<th>วันที่</th>
		<th>hn</th>
		<th>ชื่อ/นามสกุล</th>
		<th>จำนวนรายการ</th>
	</tr>
	  </thead>
	  <tbody>
		  <?php $i=0; do{ $i++; ?>
		  <tr onClick="window.location.href='med_reconcile.php?do=link&hn=<?php echo $row_rs_med['hn']; ?>&vstdate=<?php echo date_db2th($row_rs_med['vstdate2']); ?>'">
			  <td><?php echo $i; ?></td>
			  <td><?php echo dateThai($row_rs_med['vstdate2']); ?></td>
			  <td><?php echo $row_rs_med['hn']; ?></td>
			  <td><?php echo $row_rs_med['ptname']; ?></td>
			  <td><?php echo $row_rs_med['count_drug']; ?></td>
		  </tr>
		  <?php }while($row_rs_med = mysql_fetch_assoc($rs_med)); ?>
	  </tbody>

</body>
</html>