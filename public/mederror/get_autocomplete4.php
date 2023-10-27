<?php
require_once('Connections/hos.php');
$q = strtolower($_GET["q"]);
if (!$q) return;

mysql_select_db($database_hos, $hos);
$query_doctor = "select name from drugitems  where name like '%$drug%'  order by name";
$doctor = mysql_query($query_doctor, $hos) or die(mysql_error());
//$row_doctor = mysql_fetch_assoc($doctor);
?>

<head>
</head>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=tis-620" />
<body>
<? 
while($rs = mysql_fetch_array($doctor)) {
	$cname = $rs['name'];
//     $dcode = $rs['doctorcode'];
	echo "$cname\n";
}

?>
</body>
</html>