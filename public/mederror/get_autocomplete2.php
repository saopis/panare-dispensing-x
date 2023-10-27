<?php
require_once('../Connections/hos.php');
$q = strtolower($_GET["q"]);
if (!$q) return;

$like1= iconv( 'UTF-8','TIS-620', $person_error);
mysql_select_db($database_hos, $hos);
$query_doctor = "select name,code from doctor  where name like '$like1%' and name!='' order by name";
$doctor = mysql_query($query_doctor, $hos) or die(mysql_error());
//$row_doctor = mysql_fetch_assoc($doctor);
?>
<? 
while($rs = mysql_fetch_array($doctor)) {
	$cname =  $rs['name'];
     $dcode = $rs['doctorcode'];
	echo "$cname\n";
}
?>
