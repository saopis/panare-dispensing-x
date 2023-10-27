<?php
require_once('../Connections/hos.php');
$q = strtolower($_GET["q"]);
if (!$q) return;

mysql_select_db($database_hos, $hos);
$query_doctor = "select name,code from doctor  where name like '$reporter%' order by name";
$doctor = mysql_query($query_doctor, $hos) or die(mysql_error());
$row_doctor = mysql_fetch_assoc($doctor);
?>
<? 
while($rs = mysql_fetch_array($doctor)) {
	$cname = $rs['name'];
	echo "$cname\n";
}
?>