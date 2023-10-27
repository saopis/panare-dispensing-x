<?php require_once('Connections/hos.php'); ?>
<?php include('include/function.php'); ?>
<?php
//===== setting ==========//
mysql_select_db($database_hos, $hos);
$query_rs_monograph = "select image1 from drugitems_picture where icode='".$_GET['icode']."'";
//echo $query_rs_monograph;
$rs_monograph = mysql_query($query_rs_monograph, $hos) or die(mysql_error());
$row_rs_monograph = mysql_fetch_assoc($rs_monograph);
$totalRows_rs_monograph = mysql_num_rows($rs_monograph);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<div align="center">
<img src="data:image/jpeg;base64,<?php echo base64_encode($row_rs_monograph['image1']); ?> " vlign="middle" border="0" style="border-radius: 8px; border:solid 1px #E3E1E1; max-width: 700px; height: auto" class="image">
</div>
</body>
</html>