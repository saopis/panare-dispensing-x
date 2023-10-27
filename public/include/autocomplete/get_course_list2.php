<?php require_once('../../Connections/hos.php'); ?>
<?php
//require_once "config.php";
$q = strtolower($_GET["q"]);
if (!$q) return;

$q = strtolower($_GET["q"]);
$sql = "select DISTINCT course_name as course_name, course_id from course where course_name LIKE '%$q%'";
$rsd = mysql_query($sql, $hos) or die(mysql_error());

while($rs = mysql_fetch_array($rsd)) {
	$cid = $rs['course_id'];
	$cname = $rs['course_name'];
	echo "$cname|$cid\n";
}
?><p><font color="#000000">recognize </font></p>
