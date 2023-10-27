<?php require_once('../Connections/mederror.php'); ?>
<?php
mysql_select_db($database_mederror, $mederror);
$query_Recordset1 = "SELECT * FROM hospcode where hospcode='$hospcode' and hosptype = '$hosptype'";
$Recordset1 = mysql_query($query_Recordset1, $mederror) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$books = mysql_num_rows($Recordset1);
$hospcode =$row_Recordset1['hospcode'];
$hosptype =$row_Recordset1['hosptype'];
?>
<?php
mysql_free_result($Recordset1);
?>
